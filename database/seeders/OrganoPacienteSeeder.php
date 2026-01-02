<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrganoPacienteSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Pacientes
        $pacientes = DB::table('pacientes')
            ->orderBy('id')
            ->limit(5)
            ->pluck('id')
            ->toArray();

        if (count($pacientes) < 5) {
            dump('OrganoPacienteSeeder: se necesitan al menos 5 pacientes. Seeder abortado.');
            return;
        }

        // 2) Órganos por nombre -> id (coherente con OrganoSeeder)
        $organos = DB::table('organos')->pluck('id', 'nombre');

        // 3) Helper: resolver ID de síntoma por nombre exacto (columna real: 'sintoma')
        $sid = function (string $nombreSintoma): int {
            $id = DB::table('sintomas')->where('sintoma', $nombreSintoma)->value('id');

            if (!$id) {
                throw new \RuntimeException("OrganoPacienteSeeder: síntoma no encontrado: '{$nombreSintoma}'.");
            }

            return (int) $id;
        };

        // 4) Helper: valida que todos los síntomas pertenezcan al órgano del registro
        $validarSintomasDeOrgano = function (int $organoId, array $sintomaIds, string $organoNombre): void {
            if (empty($sintomaIds)) {
                return;
            }

            $malos = DB::table('sintomas')
                ->whereIn('id', $sintomaIds)
                ->where('organo_id', '!=', $organoId)
                ->pluck('sintoma')
                ->toArray();

            if (!empty($malos)) {
                $lista = implode(', ', $malos);
                throw new \RuntimeException(
                    "OrganoPacienteSeeder: inconsistencia. Síntomas no pertenecen al órgano '{$organoNombre}': {$lista}"
                );
            }
        };

        $now = Carbon::now();

        /**
         * NOTA:
         * - Ya NO usamos IDs fijos en sintomas_asociados.
         * - Los resolvemos por nombre (estable).
         * - Validamos coherencia órgano <-> síntoma.
         */
        $registros = [
            [
                'paciente_id' => $pacientes[0],
                'organo_nombre' => 'Tracto gastrointestinal',
                'score_nih' => '3',
                'fecha_evaluacion' => $now->copy()->subDays(10)->format('Y-m-d'),
                'sintomas_asociados_nombres' => [
                    'Diarrea con sangre',
                    'Diarrea acuosa',
                    'Dolor abdominal',
                ],
                'comentario' => 'Diarrea abundante con sangrado y dolor abdominal intenso.',
            ],
            [
                'paciente_id' => $pacientes[1],
                'organo_nombre' => 'Hígado',
                'score_nih' => '2',
                'fecha_evaluacion' => $now->copy()->subDays(7)->format('Y-m-d'),
                'sintomas_asociados_nombres' => [
                    'Hiperbilirrubinemia',
                    'ALT elevada',
                ],
                'comentario' => 'Bilirrubina y transaminasas elevadas de forma moderada.',
            ],
            [
                'paciente_id' => $pacientes[2],
                'organo_nombre' => 'Ojos',
                'score_nih' => '1',
                'fecha_evaluacion' => $now->copy()->subDays(5)->format('Y-m-d'),
                'sintomas_asociados_nombres' => [
                    'Ojo seco',
                ],
                'comentario' => 'Sequedad ocular leve, sin impacto en actividades diarias.',
            ],
            [
                'paciente_id' => $pacientes[3],
                'organo_nombre' => 'Pulmones',
                'score_nih' => '3',
                'fecha_evaluacion' => $now->copy()->subDays(3)->format('Y-m-d'),
                'sintomas_asociados_nombres' => [
                    'Disnea',
                    'Tos seca',
                ],
                'comentario' => 'Función pulmonar reducida con disnea marcada y tos persistente.',
            ],
            [
                'paciente_id' => $pacientes[4],
                'organo_nombre' => 'Piel',
                'score_nih' => '2',
                'fecha_evaluacion' => $now->copy()->subDays(2)->format('Y-m-d'),
                'sintomas_asociados_nombres' => [
                    'Exantema maculopapular',
                    'Cambios escleróticos de la piel',
                ],
                'comentario' => 'Lesiones cutáneas maculopapulares y cambios escleróticos superficiales.',
            ],
        ];

        foreach ($registros as $r) {
            $organoId = $organos[$r['organo_nombre']] ?? null;

            if (!$organoId) {
                dump("OrganoPacienteSeeder: órgano '{$r['organo_nombre']}' no encontrado. Registro saltado.");
                continue;
            }

            // Resolver IDs reales por nombre
            $sintomaIds = array_map($sid, $r['sintomas_asociados_nombres']);

            // Validación de coherencia órgano <- síntomas
            $validarSintomasDeOrgano($organoId, $sintomaIds, $r['organo_nombre']);

            DB::table('organo_paciente')->updateOrInsert(
                [
                    'paciente_id' => $r['paciente_id'],
                    'organo_id' => $organoId,
                ],
                [
                    'score_nih' => $r['score_nih'],
                    'fecha_evaluacion' => $r['fecha_evaluacion'],
                    'sintomas_asociados' => json_encode(array_values($sintomaIds)),
                    'comentario' => $r['comentario'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
