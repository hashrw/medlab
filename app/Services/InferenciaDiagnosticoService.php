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
    public function ejecutar(Paciente $paciente): ?Diagnostico
{
    $sintomasActivos = $paciente->sintomas()->pluck('sintomas.id')->map(fn($id) => (int) $id)->toArray();
    dump('🧠 Síntomas activos:', $sintomasActivos);

    $reglas = ReglaDecision::all();
    $organosPaciente = $paciente->organos->keyBy('nombre');
    dump('🧠 Órganos detectados:', $organosPaciente->keys()->toArray());

    foreach ($reglas as $regla) {
        $condiciones = $regla->condiciones;
        dump('🔍 Evaluando regla:', $regla->nombre_regla);

        $cumple = true;

        foreach ($condiciones as $organoNombre => $criterios) {
            dump('🧩 Verificando órgano en condiciones:', $organoNombre);

            if (!isset($organosPaciente[$organoNombre])) {
                dump("❌ Órgano $organoNombre no encontrado en paciente");
                $cumple = false;
                break;
            }

            $organo = $organosPaciente[$organoNombre];
            $scorePaciente = strtolower(trim($organo->pivot->score));
            $scoreEsperado = strtolower(trim($criterios['score'] ?? ''));

            dump("🧪 Comparando score ($organoNombre):", [
                'esperado' => $scoreEsperado,
                'paciente' => $scorePaciente
            ]);

            if ($scorePaciente !== $scoreEsperado) {
                dump("❌ Score no coincide en $organoNombre");
                $cumple = false;
                break;
            }

            $sintomasEsperados = collect($criterios['sintomas'] ?? [])->map(fn($id) => (int) $id);
            $faltantes = $sintomasEsperados->diff($sintomasActivos);

            dump("📋 Comparando síntomas esperados para $organoNombre:", [
                'esperados' => $sintomasEsperados->toArray(),
                'faltantes' => $faltantes->toArray()
            ]);

            if ($faltantes->isNotEmpty()) {
                dump("❌ No se cumplen todos los síntomas requeridos");
                $cumple = false;
                break;
            }
        }

        if (!$cumple) {
            dump("⏩ Saltando regla: {$regla->nombre_regla}");
            continue;
        }

        dump("✅ Regla inferida:", $regla->nombre_regla);
        dd('📌 Ejecución de diagnóstico con éxito.');

        // Si deseas continuar la ejecución real, quita el dd() de arriba y usa esta transacción:
        return DB::transaction(function () use ($paciente, $regla, $sintomasActivos) {
            $datos = $regla->diagnostico;

            if (isset($datos['f_trasplante'])) {
                $datos['dias_desde_trasplante'] = Carbon::parse($datos['f_trasplante'])->diffInDays(now());
            }

            $diagnostico = Diagnostico::create($datos);
            $diagnostico->pacientes()->attach($paciente->id);
            $diagnostico->enfermedades()->attach($paciente->enfermedad_id);

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

    dump('⚠️ No se cumplió ninguna regla');
    return null;
}

}
