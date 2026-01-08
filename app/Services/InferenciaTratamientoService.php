<?php

namespace App\Services;

use App\Models\Diagnostico;
use App\Models\Tratamiento;
use App\Models\Medicamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InferenciaTratamientoService
{
    public function inferirParaDiagnostico(Diagnostico $diagnostico, int $medicoId): ?Tratamiento
    {
        // Si no hay grado concluyente, no se crea tratamiento
        $grado = $diagnostico->grado_eich ?? null;
        if (!$grado || $grado === 'no_concluyente') {
            return null;
        }

        // 2) Resolver paciente (según tu modelo M:M, elige uno)
        $pacienteId = $diagnostico->paciente_id;

        if (!$pacienteId) {
            throw new \LogicException("Diagnóstico {$diagnostico->id} sin paciente asociado.");
        }


        // 3) Evitar duplicados: un tratamiento inferido por diagnóstico
        $yaExiste = Tratamiento::query()
            ->where('paciente_id', $pacienteId)
            ->where('diagnostico_id', $diagnostico->id)
            ->exists();

        if ($yaExiste) {
            return Tratamiento::where('paciente_id', $pacienteId)
                ->where('diagnostico_id', $diagnostico->id)
                ->latest('id')
                ->first();
        }

        // 4) Selección de plan terapéutico (v1: solo por grado)
        $plan = $this->planPorGrado($grado);

        // 5) Persistir tratamiento + líneas en transacción
        return DB::transaction(function () use ($plan, $pacienteId, $medicoId, $diagnostico) {

            $trat = Tratamiento::create([
                'tratamiento' => $plan['titulo'],
                'fecha_asignacion' => Carbon::now()->toDateString(),
                'descripcion' => $plan['descripcion'],
                'paciente_id' => $pacienteId,
                'medico_id' => $medicoId,
                'diagnostico_id' => $diagnostico->id,
            ]);

            foreach ($plan['lineas'] as $linea) {
                $medId = $this->resolverMedicamentoId($linea['medicamento']);

                $trat->lineasTratamiento()->attach($medId, [
                    'fecha_ini_linea' => $linea['fecha_ini_linea'],
                    'duracion_linea' => $linea['duracion_linea'],
                    'duracion_total' => $linea['duracion_total'] ?? null,
                    'fecha_fin_linea' => $linea['fecha_fin_linea'],
                    'fecha_resp_linea' => $linea['fecha_resp_linea'] ?? null,
                    'observaciones' => $linea['observaciones'] ?? null,
                    'tomas' => $linea['tomas'] ?? null,
                ]);
            }

            return $trat;
        });
    }

    private function planPorGrado(string $grado): array
    {
        // Esto es V1 estructural. El contenido clínico real lo afinamos con tu ontología.
        $hoy = Carbon::now();
        $ini = $hoy->toDateString();
        $fin14 = $hoy->copy()->addDays(14)->toDateString();

        return match ($grado) {
            'leve' => [
                'titulo' => 'Plan EICH leve (propuesta)',
                'descripcion' => 'Tratamiento de soporte y control clínico según afectación.',
                'lineas' => [
                    [
                        'medicamento' => 'soporte_general', // alias en tu tabla medicamentos
                        'fecha_ini_linea' => $ini,
                        'duracion_linea' => 14,
                        'fecha_fin_linea' => $fin14,
                        'tomas' => null,
                        'observaciones' => 'Ajustar según órgano afectado y evolución.',
                    ],
                ],
            ],
            'moderada' => [
                'titulo' => 'Plan EICH moderada (propuesta)',
                'descripcion' => 'Primera línea sistémica + soporte.',
                'lineas' => [
                    [
                        'medicamento' => 'prednisona',
                        'fecha_ini_linea' => $ini,
                        'duracion_linea' => 14,
                        'fecha_fin_linea' => $fin14,
                        'tomas' => 'según pauta',
                        'observaciones' => 'Pauta inicial; reevaluar respuesta.',
                    ],
                ],
            ],
            'severa' => [
                'titulo' => 'Plan EICH severa (propuesta)',
                'descripcion' => 'Manejo intensivo y escalado según respuesta.',
                'lineas' => [
                    [
                        'medicamento' => 'prednisona',
                        'fecha_ini_linea' => $ini,
                        'duracion_linea' => 14,
                        'fecha_fin_linea' => $fin14,
                        'tomas' => 'según pauta',
                        'observaciones' => 'Requiere valoración especializada.',
                    ],
                ],
            ],
            default => [
                'titulo' => 'Plan no definido (propuesta)',
                'descripcion' => 'No existe plan asociado a este grado.',
                'lineas' => [],
            ],
        };
    }

    private function resolverMedicamentoId(string $aliasOrName): int
    {
        $m = Medicamento::query()
            ->where('alias', $aliasOrName)
            ->orWhere('nombre', $aliasOrName)
            ->first();

        if (!$m) {
            throw new \RuntimeException("Medicamento no encontrado para '{$aliasOrName}'.");
        }

        return (int) $m->id;
    }
}
