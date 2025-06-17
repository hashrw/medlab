<?php

namespace App\Services;

use App\Models\Paciente;
use App\Models\ReglaDecision;
use App\Models\Diagnostico;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Servicio responsable de inferir diagnósticos clínicos estructurados
 * usando reglas definidas en la base de datos.
 */
class InferenciaDiagnosticoService
{
    /**
     * Ejecuta la inferencia de diagnóstico para un paciente dado.
     *
     * @param Paciente $paciente
     * @return Diagnostico|null
     */
    public function ejecutar(Paciente $paciente): ?Diagnostico
    {
        $sintomasActivos = $paciente->sintomas()->pluck('sintomas.id')->toArray();
        $reglas = ReglaDecision::all();

        foreach ($reglas as $regla) {
            $condiciones = $regla->condiciones;
            /*dd([
                'regla_id' => $regla->id,
                'nombre' => $regla->nombre_regla,
                'condiciones_crudas' => $regla->getRawOriginal('condiciones'),
                'condiciones_array' => $regla->condiciones,
                'sintomas_regla' => $regla->condiciones['sintomas'] ?? [],
                'sintomas_paciente' => $sintomasActivos,
                'diff_faltantes' => array_diff($regla->condiciones['sintomas'] ?? [], $sintomasActivos),
            ]);*/

            if (isset($condiciones['sintomas']) && $this->cumpleCondiciones($condiciones['sintomas'], $sintomasActivos)) {
                Log::info('📋 Requiere síntomas:', $condiciones['sintomas']);
                return DB::transaction(function () use ($paciente, $regla, $sintomasActivos) {
                    $datos = $regla->diagnostico;

                    // Si la regla incluye una fecha de trasplante, calcula días desde trasplante
                    if (isset($datos['f_trasplante'])) {
                        $datos['dias_desde_trasplante'] = Carbon::parse($datos['f_trasplante'])->diffInDays(now());
                    }

                    // Crear el diagnóstico estructurado
                    $diagnostico = Diagnostico::create($datos);

                    // Relacionar con paciente y enfermedad
                    $diagnostico->pacientes()->attach($paciente->id);
                    $diagnostico->enfermedades()->attach($paciente->enfermedad_id);

                    // Registrar síntomas en tabla pivot diagnostico_sintoma
                    $pivotData = [];
                    $hoy = Carbon::now()->toDateString();

                    foreach ($sintomasActivos as $sintomaId) {
                        $pivotData[$sintomaId] = [
                            'fecha_diagnostico' => $hoy,
                            'score_nih' => null,
                            'origen' => 'Inferido',
                        ];
                    }

                    $diagnostico->sintomas()->sync($pivotData);
                    return $diagnostico;
                });
            }
        }

        return null;
    }

    /**
     * Verifica si los síntomas del paciente cumplen con los requisitos de la regla.
     */
    protected function cumpleCondiciones(array $condiciones, array $sintomasPaciente): bool
    {
        return empty(array_diff($condiciones, $sintomasPaciente));
    }
}
