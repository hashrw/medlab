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
                'condiciones' => json_encode([['organo_id' => 1, 'sintomas' => [['sintoma_id' => 3, 'score_nih_nih' => 1.5], ['sintoma_id' => 4, 'score_nih_nih' => 2.0]]], ['organo_id' => 4, 'sintomas' => [['sintoma_id' => 5, 'score_nih' => 1.0], ['sintoma_id' => 4, 'score_nih' => 1.5]]], ['organo_id' => 4, 'sintomas' => [['sintoma_id' => 5, 'score_nih' => 2.0], ['sintoma_id' => 6, 'score_nih' => 1.0]]], ['organo_id' => 8, 'sintomas' => [['sintoma_id' => 7, 'score_nih' => 1.5], ['sintoma_id' => 8, 'score_nih' => 2.0]]], ['organo_id' => 5, 'sintomas' => [['sintoma_id' => 9, 'score_nih' => 1.0], ['sintoma_id' => 10, 'score_nih' => 1.5]]]], JSON_UNESCAPED_UNICODE),
                'diagnostico' => json_encode(['dias_desde_trasplante' => 180, 'tipo_enfermedad' => 'crónica', 'f_trasplante' => '2024-12-01 00:00', 'f_electromiografia' => '2025-06-01 10:00', 'f_eval_injerto' => '2025-06-10 11:00', 'f_medulograma' => '2025-05-15 09:30', 'f_espirometria' => '2025-06-02 12:00'], JSON_UNESCAPED_UNICODE),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
