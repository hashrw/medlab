<?php

namespace App\Services;

use App\Models\Paciente;
use App\Models\ReglaDecision;
use App\Models\Diagnostico;
use App\Models\Origen;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InferenciaDiagnosticoService
{
    public function ejecutar(Paciente $paciente): array
    {
        $aliasesActivos = $this->obtenerAliasesActivos($paciente);
        $paciente->loadMissing('organos');
        $organosPaciente = $paciente->organos->keyBy('nombre');

        $reglas = ReglaDecision::orderBy('prioridad')->get();

        $fallback = null;

        foreach ($reglas as $regla) {
            $condiciones = $regla->condiciones ?? [];

            if (empty($condiciones)) {
                // guardamos fallback para reportarlo si no hay match real
                $fallback = $regla;
                continue;
            }

            if ($this->evaluarCondiciones($condiciones, $organosPaciente, $aliasesActivos)) {
                $diag = $this->crearDiagnosticoInferido($paciente, $regla, $aliasesActivos);
                return [$diag, null];
            }
        }

        // no match real: devolvemos fallback si existe
        return [null, $fallback];
    }


    /**
     * Devuelve aliases canónicos activos del paciente:
     *  - Se asume pivot paciente_sintoma con sintoma_id + activo=true
     *  - Se traduce sintoma_id -> alias canonical vía sintoma_aliases
     */
    private function obtenerAliasesActivos(Paciente $paciente): array
    {
        $idsActivos = $paciente->sintomas()
            ->wherePivot('activo', true)
            ->pluck('sintomas.id')
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (empty($idsActivos)) {
            return [];
        }

        return DB::table('sintoma_aliases')
            ->whereIn('sintoma_id', $idsActivos)
            ->where('tipo', 'canonical')
            ->pluck('alias')
            ->map(fn($a) => (string) $a)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Condiciones:
     * - Verifica órgano.
     * - Compara score NIH (score / score_min / score_max).
     * - Evalúa coincidencia mínima de síntomas (80%):
     *    * Si en regla vienen aliases (strings) -> compara contra $aliasesActivos.
     *    * Compatibilidad: si vienen ints (IDs) -> se convierten a alias canonical.
     */
    private function evaluarCondiciones(array $condiciones, $organosPaciente, array $aliasesActivos): bool
    {
        foreach ($condiciones as $organoNombre => $criterios) {

            if (!isset($organosPaciente[$organoNombre])) {
                return false;
            }

            $organo = $organosPaciente[$organoNombre];

            $scorePaciente = $organo->pivot->score_nih;
            $scorePaciente = is_numeric($scorePaciente) ? (int) $scorePaciente : null;

            // score exacto
            if (array_key_exists('score', $criterios) && $criterios['score'] !== null && $criterios['score'] !== '') {
                $scoreEsperado = is_numeric($criterios['score']) ? (int) $criterios['score'] : null;
                if ($scoreEsperado !== null && $scorePaciente !== $scoreEsperado) {
                    return false;
                }
            }

            // score_min
            if (array_key_exists('score_min', $criterios) && $criterios['score_min'] !== null && $criterios['score_min'] !== '') {
                $min = is_numeric($criterios['score_min']) ? (int) $criterios['score_min'] : null;
                if ($min !== null) {
                    if ($scorePaciente === null || $scorePaciente < $min) {
                        return false;
                    }
                }
            }

            // score_max
            if (array_key_exists('score_max', $criterios) && $criterios['score_max'] !== null && $criterios['score_max'] !== '') {
                $max = is_numeric($criterios['score_max']) ? (int) $criterios['score_max'] : null;
                if ($max !== null) {
                    if ($scorePaciente === null || $scorePaciente > $max) {
                        return false;
                    }
                }
            }

            // Síntomas esperados: aliases (preferente)
            $esperados = collect($criterios['sintomas'] ?? []);

            if ($esperados->isNotEmpty()) {
                // Si vienen IDs (ints), convertirlos a alias canonical
                $primer = $esperados->first();
                $esperadosAliases = is_int($primer) || ctype_digit((string) $primer)
                    ? $this->idsASAliasesCanonicos($esperados->map(fn($v) => (int) $v)->all())
                    : $esperados->map(fn($v) => (string) $v)->all();

                $esperadosAliases = collect($esperadosAliases)->unique()->values();

                $coincidentes = $esperadosAliases->intersect($aliasesActivos);
                $ratio = $coincidentes->count() / max(1, $esperadosAliases->count());

                if ($ratio < 0.8) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Crea diagnóstico 1:N (paciente_id) e inserta pivot diagnostico_sintoma
     * traduciendo alias -> sintoma_id.
     */
    private function crearDiagnosticoInferido(Paciente $paciente, ReglaDecision $regla, array $aliasesActivos): Diagnostico
    {
        return DB::transaction(function () use ($paciente, $regla, $aliasesActivos) {

            $hoy = Carbon::now()->toDateString();

            // Idempotencia 1:N
            $diagnosticoExistente = Diagnostico::where('regla_decision_id', $regla->id)
                ->where('paciente_id', $paciente->id)
                ->first();

            if ($diagnosticoExistente) {
                return $diagnosticoExistente;
            }

            $datosBase = $regla->diagnostico ?? [];
            $origenInferidoId = Origen::where('origen', 'inferido')->value('id');

            $datosDiagnostico = array_merge($datosBase, [
                'fecha_diagnostico' => $hoy,
                'paciente_id' => $paciente->id,
                'regla_decision_id' => $regla->id,
                'origen_id' => $origenInferidoId,
                'observaciones' => $regla->descripcion_clinica
                    ?? 'Diagnóstico generado automáticamente por inferencia clínica',
            ]);

            $diagnostico = Diagnostico::create($datosDiagnostico);

            // ===== Trazabilidad de síntomas =====
            // Construir pivotData: sintoma_id => [fecha_diagnostico, score_nih]
            $pivotData = [];
            $condiciones = $regla->condiciones ?? [];

            // Mapa alias -> sintoma_id (solo para aliases usados)
            $aliasesNecesarios = [];

            foreach ($condiciones as $criterios) {
                foreach (($criterios['sintomas'] ?? []) as $s) {
                    if (!is_int($s) && !ctype_digit((string) $s)) {
                        $aliasesNecesarios[] = (string) $s;
                    }
                }
            }

            $aliasToId = $this->mapAliasToSintomaId(array_unique($aliasesNecesarios));

            foreach ($condiciones as $criterios) {

                $scoreOrgano = null;
                if (array_key_exists('score', $criterios) && is_numeric($criterios['score'])) {
                    $scoreOrgano = (int) $criterios['score'];
                } elseif (array_key_exists('score_min', $criterios) && is_numeric($criterios['score_min'])) {
                    // Si la regla es >=, guardamos min como referencia
                    $scoreOrgano = (int) $criterios['score_min'];
                }

                $lista = collect($criterios['sintomas'] ?? []);

                // Soportar reglas antiguas con IDs
                if ($lista->isNotEmpty() && (is_int($lista->first()) || ctype_digit((string) $lista->first()))) {
                    $ids = $lista->map(fn($v) => (int) $v)->all();
                    // Filtrar por los que el paciente tenga activos (pero activo en alias, así que convertimos ids->alias y cruzamos)
                    $aliasesDeIds = $this->idsASAliasesCanonicos($ids);
                    $aliasesCoincidentes = collect($aliasesDeIds)->intersect($aliasesActivos);

                    // convertir aliasesCoincidentes a ids usando aliasToId
                    $idsCoincidentes = $aliasesCoincidentes
                        ->map(fn($a) => $aliasToId[(string) $a] ?? null)
                        ->filter()
                        ->map(fn($id) => (int) $id)
                        ->all();

                    foreach ($idsCoincidentes as $sintomaId) {
                        $pivotData[$sintomaId] = [
                            'fecha_diagnostico' => $hoy,
                            'score_nih' => $scoreOrgano,
                        ];
                    }

                } else {
                    // Reglas nuevas: aliases
                    $aliasesRegla = $lista->map(fn($v) => (string) $v)->unique()->values();
                    $aliasesCoincidentes = $aliasesRegla->intersect($aliasesActivos);

                    foreach ($aliasesCoincidentes as $alias) {
                        $sintomaId = $aliasToId[(string) $alias] ?? null;
                        if ($sintomaId) {
                            $pivotData[(int) $sintomaId] = [
                                'fecha_diagnostico' => $hoy,
                                'score_nih' => $scoreOrgano,
                            ];
                        }
                    }
                }
            }

            if (!empty($pivotData)) {
                $diagnostico->sintomas()->sync($pivotData);
            }

            return $diagnostico;
        });
    }

    /**
     * Convierte sintoma_id[] a alias canonical[].
     */
    private function idsASAliasesCanonicos(array $sintomaIds): array
    {
        if (empty($sintomaIds))
            return [];

        return DB::table('sintoma_aliases')
            ->whereIn('sintoma_id', $sintomaIds)
            ->where('tipo', 'canonical')
            ->pluck('alias')
            ->map(fn($a) => (string) $a)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Devuelve mapa alias canonical -> sintoma_id.
     */
    private function mapAliasToSintomaId(array $aliases): array
    {
        if (empty($aliases))
            return [];

        return DB::table('sintoma_aliases')
            ->whereIn('alias', $aliases)
            ->where('tipo', 'canonical')
            ->pluck('sintoma_id', 'alias')
            ->mapWithKeys(fn($id, $alias) => [(string) $alias => (int) $id])
            ->all();
    }
}
