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
    /**
     * EJECUCIÓN DEL MOTOR DE INFERENCIA
     *
     * Flujo general:
     *  1. Obtiene estado clínico actual del paciente (síntomas y órganos).
     *  2. Recorre reglas clínicas en orden.
     *  3. Aplica la primera regla que cumple.
     *  4. Crea (o reutiliza) un diagnóstico inferido.
     */
    public function ejecutar(Paciente $paciente): ?Diagnostico
    {
        // ===== BLOQUE 1: CONTEXTO CLÍNICO DEL PACIENTE =====

        // Síntomas activos del paciente (IDs únicos)
        $sintomasActivos = $this->obtenerSintomasActivos($paciente);

        // Órganos evaluados del paciente con score NIH (indexados por nombre)
        $organosPaciente = $paciente->organos->keyBy('nombre');

        // ===== BLOQUE 2: EVALUACIÓN DE REGLAS CLÍNICAS =====

        $reglas = ReglaDecision::orderBy('prioridad')->get();

        foreach ($reglas as $regla) {
            $condiciones = $regla->condiciones ?? [];

            if ($this->evaluarCondiciones($condiciones, $organosPaciente, $sintomasActivos)) {
                // ===== BLOQUE 3: IDempotencia + creación del diagnóstico =====
                return $this->crearDiagnosticoInferido($paciente, $regla, $sintomasActivos);
            }
        }

        // Ninguna regla aplica
        return null;
    }

    /**
     * BLOQUE FUNCIONAL:
     * Obtención de síntomas activos del paciente.
     */
    private function obtenerSintomasActivos(Paciente $paciente): array
    {
        return $paciente->sintomas()
            ->pluck('sintomas.id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * BLOQUE FUNCIONAL:
     * Evaluación de condiciones clínicas de una regla.
     *
     * - Verifica existencia del órgano.
     * - Compara score NIH.
     * - Evalúa coincidencia mínima de síntomas (80%).
     */
    private function evaluarCondiciones(array $condiciones, $organosPaciente, array $sintomasActivos): bool
    {
        foreach ($condiciones as $organoNombre => $criterios) {

            if (!isset($organosPaciente[$organoNombre])) {
                return false;
            }

            $organo = $organosPaciente[$organoNombre];

            // Comparación de score NIH
            $scorePaciente = trim((string) $organo->pivot->score_nih);
            $scoreEsperado = trim((string) ($criterios['score'] ?? ''));

            if ($scoreEsperado !== '' && $scorePaciente !== $scoreEsperado) {
                return false;
            }

            // Evaluación de síntomas
            $sintomasEsperados = collect($criterios['sintomas'] ?? [])
                ->map(fn ($id) => (int) $id);

            if ($sintomasEsperados->isNotEmpty()) {
                $coincidentes = $sintomasEsperados->intersect($sintomasActivos);
                $ratio = $coincidentes->count() / $sintomasEsperados->count();

                if ($ratio < 0.8) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * BLOQUE FUNCIONAL:
     * Creación del diagnóstico inferido con:
     *  - Idempotencia (no duplicar mismo diagnóstico inferido).
     *  - Trazabilidad mínima (regla aplicada + fecha).
     *  - Asociación determinista de síntomas (desde la regla).
     */
    private function crearDiagnosticoInferido(
        Paciente $paciente,
        ReglaDecision $regla,
        array $sintomasActivos
    ): Diagnostico {
        return DB::transaction(function () use ($paciente, $regla, $sintomasActivos) {

            $hoy = Carbon::now()->toDateString();

            // ===== BLOQUE 4: IDEMPOTENCIA =====
            // Si ya existe un diagnóstico inferido para este paciente y esta regla, se reutiliza.
            $diagnosticoExistente = Diagnostico::where('regla_decision_id', $regla->id)
                ->whereHas('pacientes', fn ($q) => $q->where('paciente_id', $paciente->id))
                ->first();

            if ($diagnosticoExistente) {
                return $diagnosticoExistente;
            }

            // ===== BLOQUE 5: CREACIÓN DEL DIAGNÓSTICO =====

            $datosBase = $regla->diagnostico ?? [];

            $origenInferidoId = Origen::where('origen', 'inferido')->value('id');

            $datosDiagnostico = array_merge($datosBase, [
                'fecha_diagnostico'  => $hoy,
                'regla_decision_id'  => $regla->id,
                'origen_id'          => $origenInferidoId,
                'observaciones'     => $regla->descripcion_clinica
                    ?? 'Diagnóstico generado automáticamente por inferencia clínica',
            ]);

            $diagnostico = Diagnostico::create($datosDiagnostico);

            // Asociación paciente ↔ diagnóstico
            $diagnostico->pacientes()->attach($paciente->id);

            // ===== BLOQUE 6: TRAZABILIDAD DE SÍNTOMAS =====

            $pivotData = [];
            $condiciones = $regla->condiciones ?? [];

            foreach ($condiciones as $criterios) {
                $scoreOrgano = $criterios['score'] ?? null;

                $sintomasRegla = collect($criterios['sintomas'] ?? [])
                    ->map(fn ($id) => (int) $id);

                $sintomasCoincidentes = $sintomasRegla->intersect($sintomasActivos);

                foreach ($sintomasCoincidentes as $sintomaId) {
                    if (!isset($pivotData[$sintomaId])) {
                        $pivotData[$sintomaId] = [
                            'fecha_diagnostico' => $hoy,
                            'score_nih'         => $scoreOrgano !== null ? (int) $scoreOrgano : null,
                        ];
                    }
                }
            }

            if (!empty($pivotData)) {
                $diagnostico->sintomas()->sync($pivotData);
            }

            return $diagnostico;
        });
    }
}
