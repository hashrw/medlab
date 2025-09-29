<?php

namespace App\Services;

use App\Models\Paciente;
use App\Models\ReglaDecision;
use App\Models\Diagnostico;
use App\Models\SintomaAlias;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InferenciaDiagnosticoService
{
    /**
     * Ejecuta el motor de inferencia sobre un paciente
     */
    public function ejecutar(Paciente $paciente): ?Diagnostico
    {
        // 1. Obtener síntomas activos del paciente (IDs canónicos)
        $sintomasActivos = $this->mapearSintomasCanonicos(
            $paciente->sintomas()->pluck('sintomas.id')->toArray()
        );

        // 2. Obtener órganos del paciente con score NIH y síntomas asociados
        $organosPaciente = $paciente->organos->keyBy('nombre');

        // 3. Cargar reglas de decisión
        $reglas = ReglaDecision::all();

        foreach ($reglas as $regla) {
            $condiciones = $regla->condiciones;

            // Validar condiciones órgano por órgano
            $cumple = $this->evaluarCondiciones($condiciones, $organosPaciente, $sintomasActivos);

            if ($cumple) {
                return $this->crearDiagnosticoInferido($paciente, $regla, $sintomasActivos);
            }
        }

        return null;
    }

    /**
     * Reemplaza IDs de síntomas por sus equivalentes canónicos (si venían de un alias)
     */
    private function mapearSintomasCanonicos(array $sintomasIds): array
    {
        $canonicos = [];

        foreach ($sintomasIds as $id) {
            $alias = SintomaAlias::where('id', $id)->first();

            if ($alias) {
                $canonicos[] = $alias->sintoma_id;
            } else {
                $canonicos[] = $id;
            }
        }

        return array_unique($canonicos);
    }

    /**
     * Evalúa si las condiciones de la regla se cumplen en el paciente
     */
    private function evaluarCondiciones(array $condiciones, $organosPaciente, array $sintomasActivos): bool
    {
        foreach ($condiciones as $organoNombre => $criterios) {
            if (!isset($organosPaciente[$organoNombre])) {
                return false;
            }

            $organo = $organosPaciente[$organoNombre];

            // Comparar score NIH
            $scorePaciente = strtolower(trim($organo->pivot->score_nih));
            $scoreEsperado = strtolower(trim($criterios['score'] ?? ''));

            if ($scoreEsperado && $scorePaciente !== $scoreEsperado) {
                return false;
            }

            // Comparar síntomas esperados
            $sintomasEsperados = collect($criterios['sintomas'] ?? [])->map(fn($id) => (int) $id);
            $faltantes = $sintomasEsperados->diff($sintomasActivos);

            // Permitir cierto margen (≥80% de coincidencia)
            $totalEsperados = $sintomasEsperados->count();
            $totalCoinciden = $totalEsperados - $faltantes->count();

            if ($totalEsperados > 0) {
                $porcentajeCoincidencia = $totalCoinciden / $totalEsperados;
                if ($porcentajeCoincidencia < 0.8) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Crea el diagnóstico inferido en la base de datos
     */
    private function crearDiagnosticoInferido(Paciente $paciente, ReglaDecision $regla, array $sintomasActivos): Diagnostico
    {
        return DB::transaction(function () use ($paciente, $regla, $sintomasActivos) {
            $datos = $regla->diagnostico;

            // Calcular días desde trasplante (si aplica)
            if ($paciente->trasplante && $paciente->trasplante->fecha_trasplante) {
                $datos['dias_desde_trasplante'] = $paciente->trasplante->dias_desde_trasplante;
            }

            // Crear diagnóstico
            $diagnostico = Diagnostico::create(array_merge($datos, [
                'origen' => 'Inferido',
                'regla_decision_id' => $regla->id,
            ]));

            // Asociar al paciente
            $diagnostico->pacientes()->attach($paciente->id);

            // Asociar síntomas inferidos
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
