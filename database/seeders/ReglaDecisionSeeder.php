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
                'nombre_regla' => 'Diagnóstico EICH crónica completo inferido automáticamente',
                'condiciones' => json_encode([
                    'sintomas' => [1, 3, 5, 7, 9]
                ]),
                'diagnostico' => json_encode([
                    'dias_desde_trasplante' => 180,
                    'tipo_enfermedad' => 'crónica',
                    'f_trasplante' => '2024-12-01 00:00',
                    'f_electromiografia' => '2025-06-01 10:00',
                    'f_eval_injerto' => '2025-06-10 11:00',
                    'f_medulograma' => '2025-05-15 09:30',
                    'f_espirometria' => '2025-06-08 08:45',
                    'f_esplenectomia' => '2025-04-01 12:00',
                    'hipoalbuminemia' => 'sí',
                    'observaciones' => 'Paciente con evolución estable. Monitorización continua sugerida.',
                    'estado_id' => 1,
                    'comienzo_id' => 2,
                    'infeccion_id' => 3
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_regla' => 'Diagnóstico EICH aguda completo inferido automáticamente',
                'condiciones' => json_encode([
                    'sintomas' => [2, 4, 6, 8, 10]
                ]),
                'diagnostico' => json_encode([
                    'dias_desde_trasplante' => 90,
                    'tipo_enfermedad' => 'aguda',
                    'f_trasplante' => '2025-03-15 00:00',
                    'f_electromiografia' => '2025-06-05 09:00',
                    'f_eval_injerto' => '2025-06-12 10:30',
                    'f_medulograma' => '2025-05-20 08:15',
                    'f_espirometria' => '2025-06-10 11:45',
                    'f_esplenectomia' => '2025-05-01 13:00',
                    'hipoalbuminemia' => 'no',
                    'observaciones' => 'Síntomas gastrointestinales agudos y rechazo temprano del injerto.',
                    'estado_id' => 2,
                    'comienzo_id' => 1,
                    'infeccion_id' => 4
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
