<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrganoPacienteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('organo_paciente')->insert([

            // Paciente 1 con afectación gastrointestinal grave
            [
                'paciente_id' => 1,
                'organo_id' => 1, // Tracto gastrointestinal
                'score_nih' => '3', // Grave
                'fecha_evaluacion' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'sintomas_asociados' => json_encode([
                    1, // Diarrea con sangre
                    2, // Diarrea acuosa
                    3  // Dolor abdominal
                ]),
                'comentario' => 'Diarrea abundante con sangrado y dolor abdominal intenso.',
            ],

            // Paciente 2 con afectación hepática moderada
            [
                'paciente_id' => 2,
                'organo_id' => 2, // Hígado
                'score_nih' => '2', // Moderado
                'fecha_evaluacion' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'sintomas_asociados' => json_encode([
                    6,  // Hiperbilirrubinemia
                    15  // ALT elevada
                ]),
                'comentario' => 'Bilirrubina y transaminasas elevadas de forma moderada.',
            ],

            // Paciente 3 con afectación ocular leve
            [
                'paciente_id' => 3,
                'organo_id' => 5, // Ojos
                'score_nih' => '1', // Leve
                'fecha_evaluacion' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'sintomas_asociados' => json_encode([
                    8 // Ojo seco
                ]),
                'comentario' => 'Sequedad ocular leve, sin impacto en actividades diarias.',
            ],

            // Paciente 4 con afectación pulmonar severa
            [
                'paciente_id' => 4,
                'organo_id' => 8, // Pulmones
                'score_nih' => '3', // Severo
                'fecha_evaluacion' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'sintomas_asociados' => json_encode([
                    26, // Disnea
                    27  // Tos seca
                ]),
                'comentario' => 'Función pulmonar reducida con disnea marcada y tos persistente.',
            ],

            // Paciente 5 con afectación cutánea moderada
            [
                'paciente_id' => 5,
                'organo_id' => 7, // Piel
                'score_nih' => '2', // Moderado
                'fecha_evaluacion' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'sintomas_asociados' => json_encode([
                    7,  // Exantema maculopapular
                    19  // Cambios escleróticos de la piel
                ]),
                'comentario' => 'Lesiones cutáneas maculopapulares y cambios escleróticos superficiales.',
            ],
        ]);
    }
}
