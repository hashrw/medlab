<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrganoPacienteSeeder extends Seeder
{
    public function run(): void
    {
        // Obtenemos al menos 5 pacientes existentes
        $pacientes = DB::table('pacientes')
            ->orderBy('id')
            ->limit(5)
            ->pluck('id')
            ->toArray();

        if (count($pacientes) < 5) {
            dump('OrganoPacienteSeeder: se necesitan al menos 5 pacientes. Seeder abortado.');
            return;
        }

        // Mapear órganos por nombre → id (coherente con OrganoSeeder)
        $organos = DB::table('organos')
            ->pluck('id', 'nombre'); // ['Tracto gastrointestinal' => 1, ...]

        $now = Carbon::now();

        $registros = [

            // Paciente 1 con afectación gastrointestinal grave
            [
                'paciente_id' => $pacientes[0],
                'organo_nombre' => 'Tracto gastrointestinal',
                'score_nih' => '3', // Grave
                'fecha_evaluacion' => $now->copy()->subDays(10)->format('Y-m-d'),
                'sintomas_asociados' => json_encode([1, 2, 3]), // diarrea con sangre, acuosa, dolor abdominal
                'comentario' => 'Diarrea abundante con sangrado y dolor abdominal intenso.',
            ],

            // Paciente 2 con afectación hepática moderada
            [
                'paciente_id' => $pacientes[1],
                'organo_nombre' => 'Hígado',
                'score_nih' => '2', // Moderado
                'fecha_evaluacion' => $now->copy()->subDays(7)->format('Y-m-d'),
                // 6 = Hiperbilirrubinemia, 19 = ALT elevada
                'sintomas_asociados' => json_encode([6, 19]),
                'comentario' => 'Bilirrubina y transaminasas elevadas de forma moderada.',
            ],

            // Paciente 3 con afectación ocular leve
            [
                'paciente_id' => $pacientes[2],
                'organo_nombre' => 'Ojos',
                'score_nih' => '1', // Leve
                'fecha_evaluacion' => $now->copy()->subDays(5)->format('Y-m-d'),
                // 8 = Ojo seco
                'sintomas_asociados' => json_encode([8]),
                'comentario' => 'Sequedad ocular leve, sin impacto en actividades diarias.',
            ],

            // Paciente 4 con afectación pulmonar severa
            [
                'paciente_id' => $pacientes[3],
                'organo_nombre' => 'Pulmones',
                'score_nih' => '3', // Severo
                'fecha_evaluacion' => $now->copy()->subDays(3)->format('Y-m-d'),
                // 29 = Disnea, 30 = Tos seca
                'sintomas_asociados' => json_encode([29, 30]),
                'comentario' => 'Función pulmonar reducida con disnea marcada y tos persistente.',
            ],

            // Paciente 5 con afectación cutánea moderada
            [
                'paciente_id' => $pacientes[4],
                'organo_nombre' => 'Piel',
                'score_nih' => '2', // Moderado
                'fecha_evaluacion' => $now->copy()->subDays(2)->format('Y-m-d'),
                // 7 = Exantema maculopapular, 18 = Cambios escleróticos de la piel
                'sintomas_asociados' => json_encode([7, 18]),
                'comentario' => 'Lesiones cutáneas maculopapulares y cambios escleróticos superficiales.',
            ],
        ];

        foreach ($registros as $r) {
            $organoId = $organos[$r['organo_nombre']] ?? null;

            if (!$organoId) {
                dump("OrganoPacienteSeeder: órgano '{$r['organo_nombre']}' no encontrado. Registro saltado.");
                continue;
            }

            DB::table('organo_paciente')->updateOrInsert(
                [
                    'paciente_id' => $r['paciente_id'],
                    'organo_id' => $organoId,
                ],
                [
                    'score_nih' => $r['score_nih'],
                    'fecha_evaluacion' => $r['fecha_evaluacion'],
                    'sintomas_asociados' => $r['sintomas_asociados'],
                    'comentario' => $r['comentario'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
