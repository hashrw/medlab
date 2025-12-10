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
     * Ejecuta el motor de inferencia sobre un paciente.
     * Devuelve un Diagnostico inferido o null si ninguna regla aplica.
     */
    public function ejecutar(Paciente $paciente): ?Diagnostico
    {
        // 1. Síntomas activos del paciente (IDs)
        $sintomasActivos = $this->obtenerSintomasActivos($paciente);

        // 2. Órganos del paciente con score NIH, indexados por nombre
        $organosPaciente = $paciente->organos->keyBy('nombre');

        // 3. Recorrer reglas en orden y aplicar la primera que cumpla
        $reglas = ReglaDecision::all();

        foreach ($reglas as $regla) {
            $condiciones = $regla->condiciones ?? [];

            if ($this->evaluarCondiciones($condiciones, $organosPaciente, $sintomasActivos)) {
                // Primera regla que cumple → creamos diagnóstico inferido
                return $this->crearDiagnosticoInferido($paciente, $regla, $sintomasActivos);
            }
        }

        return null;
    }

    /**
     * Devuelve los IDs de síntomas activos del paciente.
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
     * Evalúa si las condiciones de la regla se cumplen en el paciente.
     *
     * Estructura esperada de $condiciones:
     * [
     *   "piel" => [
     *      "score"   => "1",
     *      "sintomas"=> [4, 5]
     *   ],
     *   "gastro" => [
     *      "score"   => "3",
     *      "sintomas"=> [12, 13, 14]
     *   ],
     *   ...
     * ]
     */
    private function evaluarCondiciones(array $condiciones, $organosPaciente, array $sintomasActivos): bool
    {
        foreach ($condiciones as $organoNombre => $criterios) {
            // El paciente debe tener ese órgano evaluado
            if (!isset($organosPaciente[$organoNombre])) {
                return false;
            }

            $organo = $organosPaciente[$organoNombre];

            // Comparar score NIH (ej: "1", "2", "3", "4")
            $scorePaciente = strtolower(trim((string) $organo->pivot->score_nih));
            $scoreEsperado = strtolower(trim((string) ($criterios['score'] ?? '')));

            if ($scoreEsperado !== '' && $scorePaciente !== $scoreEsperado) {
                return false;
            }

            // Comparar síntomas esperados vs síntomas activos del paciente
            $sintomasEsperados = collect($criterios['sintomas'] ?? [])
                ->map(fn ($id) => (int) $id);

            $totalEsperados = $sintomasEsperados->count();

            if ($totalEsperados > 0) {
                $faltantes = $sintomasEsperados->diff($sintomasActivos);
                $totalCoinciden = $totalEsperados - $faltantes->count();
                $porcentajeCoincidencia = $totalCoinciden / $totalEsperados;

                // Umbral 80% de coincidencia mínima
                if ($porcentajeCoincidencia < 0.8) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Crea el diagnóstico inferido en la base de datos.
     *
     * - Usa los datos JSON de $regla->diagnostico como base.
     * - Marca origen como "inferido" vía origen_id.
     * - Asocia el paciente.
     * - Asocia los síntomas = intersección (regla ∩ síntomas activos),
     *   con el score NIH del órgano correspondiente.
     */
    private function crearDiagnosticoInferido(
        Paciente $paciente,
        ReglaDecision $regla,
        array $sintomasActivos
    ): Diagnostico {
        return DB::transaction(function () use ($paciente, $regla, $sintomasActivos) {

            $hoy = Carbon::now()->toDateString();

            // Datos base del diagnóstico desde la regla (claves = fillable de Diagnostico)
            $datosBase = $regla->diagnostico ?? [];

            // Buscar origen "inferido" en la tabla origins (columna 'origen')
            $origenInferidoId = Origen::where('origen', 'inferido')->value('id');

            $datosDiagnostico = array_merge($datosBase, [
                'fecha_diagnostico' => $hoy,
                'regla_decision_id' => $regla->id,
            ]);

            if ($origenInferidoId) {
                $datosDiagnostico['origen_id'] = $origenInferidoId;
            }

            // Crear diagnóstico inferido
            $diagnostico = Diagnostico::create($datosDiagnostico);

            // Asociar paciente (1 paciente por diagnóstico)
            $diagnostico->pacientes()->attach($paciente->id);

            // Construir datos del pivot diagnostico_sintoma
            $pivotData   = [];
            $condiciones = $regla->condiciones ?? [];

            foreach ($condiciones as $organoNombre => $criterios) {
                $scoreOrgano = $criterios['score'] ?? null;

                $sintomasRegla = collect($criterios['sintomas'] ?? [])
                    ->map(fn ($id) => (int) $id);

                // Intersección: solo síntomas que están en la regla Y están activos
                $sintomasCoincidentes = $sintomasRegla->intersect($sintomasActivos);

                foreach ($sintomasCoincidentes as $sintomaId) {
                    $pivotData[$sintomaId] = [
                        'fecha_diagnostico' => $hoy,
                        'score_nih'         => $scoreOrgano !== null ? (int) $scoreOrgano : null,
                    ];
                }
            }

            // Asociar síntomas al diagnóstico
            if (!empty($pivotData)) {
                $diagnostico->sintomas()->sync($pivotData);
            }

            return $diagnostico;
        });
    }
}
