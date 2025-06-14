<?php

namespace App\Services;

use App\Models\Paciente;
use App\Models\ReglaDecision;
use App\Models\Diagnostico;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InferenciaDiagnosticoService
{
    /**
     * Ejecuta la inferencia de diagnóstico para un paciente dado.
     *
     * @param Paciente $paciente Instancia del paciente sobre el que se evaluarán las reglas.
     * @return Diagnostico|null Retorna el diagnóstico creado si hubo coincidencia, o null si no se infiere ninguno.
     */
    public function ejecutar(Paciente $paciente): ?Diagnostico
    {
        // 1. Obtener los IDs de los síntomas activos del paciente
        $sintomasActivos = $paciente->sintomas()->pluck('sintoma_id')->toArray();

        // 2. Obtener todas las reglas activas
        $reglas = ReglaDecision::all();

        foreach ($reglas as $regla) {
            $condiciones = json_decode($regla->condiciones_json, true);

            if (isset($condiciones['sintomas']) && $this->cumpleCondiciones($condiciones['sintomas'], $sintomasActivos)) {
                // Transacción para mantener coherencia
                return DB::transaction(function () use ($paciente, $regla, $sintomasActivos) {
                    // 3. Crear diagnóstico
                    $diagnostico = Diagnostico::create([
                        'descripcion' => $regla->resultado,
                        'origen' => 'inferido',
                    ]);

                    // 4. Relacionar con paciente y enfermedad
                    $diagnostico->pacientes()->attach($paciente->id);
                    $diagnostico->enfermedades()->attach($paciente->enfermedad_id);

                    // 5. Copiar síntomas con información pivot
                    $pivotData = [];
                    $hoy = Carbon::now()->toDateString();

                    foreach ($sintomasActivos as $sintomaId) {
                        $pivotData[$sintomaId] = [
                            'fecha_diagnostico' => $hoy,
                            'score_nih' => null,
                            'validado' => false,
                        ];
                    }

                    $diagnostico->sintomas()->sync($pivotData);

                    Log::info("Diagnóstico inferido para paciente ID {$paciente->id}");

                    return $diagnostico;
                });
            }
        }

        Log::info("No se encontró diagnóstico inferido para paciente ID {$paciente->id}");
        return null;
    }

    protected function cumpleCondiciones(array $condiciones, array $sintomasPaciente): bool
    {
        return empty(array_diff($condiciones, $sintomasPaciente));
    }
}
