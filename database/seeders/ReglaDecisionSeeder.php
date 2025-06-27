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
                'nombre_regla' => 'EICH crónica cutánea leve',
                'condiciones' => json_encode([
                    'Piel' => [
                        'sintomas' => [1, 2],
                        'score' => 'Score_1_<25_SCA'
                    ]
                ]),
                'diagnostico' => json_encode([
                    'tipo_enfermedad' => 'crónica',
                    'dias_desde_trasplante' => 180,
                    'f_trasplante' => '2024-12-01 00:00',
                    'f_electromiografia' => '2025-06-01 10:00',
                    'f_eval_injerto' => '2025-06-10 11:00',
                    'f_medulograma' => '2025-05-15 09:30',
                    'f_espirometria' => '2025-06-08 08:45',
                    'f_esplenectomia' => '2025-04-01 12:00',
                    'hipoalbuminemia' => 'sí',
                    'observaciones' => 'Eritema leve y localizado compatible con inicio de EICH crónica.',
                    'estado_id' => 1,
                    'comienzo_id' => 2,
                    'infeccion_id' => 3
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_regla' => 'EICH aguda hepática moderada',
                'condiciones' => json_encode([
                    'Hígado' => [
                        'sintomas' => [7, 8],
                        'score' => 'SCORE_2_3-6mg/dl'
                    ]
                ]),
                'diagnostico' => json_encode([
                    'tipo_enfermedad' => 'aguda',
                    'dias_desde_trasplante' => 50,
                    'f_trasplante' => '2025-05-05 00:00',
                    'f_electromiografia' => '2025-06-08 10:00',
                    'f_eval_injerto' => '2025-06-10 09:45',
                    'f_medulograma' => '2025-06-06 08:30',
                    'f_espirometria' => '2025-06-07 09:00',
                    'f_esplenectomia' => '2025-05-28 10:30',
                    'hipoalbuminemia' => 'sí',
                    'observaciones' => 'Elevación moderada de bilirrubina compatible con EICH hepática.',
                    'estado_id' => 2,
                    'comienzo_id' => 1,
                    'infeccion_id' => 3
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_regla' => 'EICH aguda GI leve',
                'condiciones' => json_encode([
                    'Tracto gastrointestinal' => [
                        'sintomas' => [2, 5],
                        'score' => 'Score_1_<500ml/day'
                    ]
                ]),
                'diagnostico' => json_encode([
                    'tipo_enfermedad' => 'aguda',
                    'dias_desde_trasplante' => 45,
                    'f_trasplante' => '2025-05-01 00:00',
                    'f_electromiografia' => '2025-06-10 09:00',
                    'f_eval_injerto' => '2025-06-11 10:00',
                    'f_medulograma' => '2025-06-09 08:30',
                    'f_espirometria' => '2025-06-10 08:45',
                    'f_esplenectomia' => '2025-06-01 10:00',
                    'hipoalbuminemia' => 'no',
                    'observaciones' => 'Afectación gastrointestinal leve.',
                    'estado_id' => 1,
                    'comienzo_id' => 1,
                    'infeccion_id' => 2
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
