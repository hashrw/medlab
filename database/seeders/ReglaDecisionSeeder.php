<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReglaDecisionSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('regla_decisions')->insert([
            [
                'nombre_regla' => 'Diagnóstico EICH crónica inferido por sistema basado en órganos y score_nihs',
                'condiciones' => json_encode([
                    'Tracto gastrointestinal' => [
                        'score' => 'Score_3_1000-1500ml/day',
                        'sintomas' => [3, 4],
                    ],
                    'Boca' => [
                        'score' => 'Score_1',
                        'sintomas' => [5, 6],
                    ],
                    'Pulmones' => [
                        'score' => 'Score_1',
                        'sintomas' => [7, 8],
                    ],
                    'Ojos' => [
                        'score' => 'Score_1',
                        'sintomas' => [9, 10],
                    ],
                ], JSON_UNESCAPED_UNICODE),
                'diagnostico' => json_encode([
                    'tipo_enfermedad' => 'crónica',
                    'origen' => 'inferido',
                    'observaciones' => 'Tratamiento pendiente de pruebas',

                ], JSON_UNESCAPED_UNICODE),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

