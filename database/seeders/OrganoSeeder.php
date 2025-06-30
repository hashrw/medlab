<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('organos')->insert([
            [
                'nombre' => "Tracto gastrointestinal",
            ],
            [
                'nombre' => "Boca",
            ],
            [
                'nombre' => "Genitales masculinos",
            ],
            [
                'nombre' => "Hígado",
            ],
            [
                'nombre' => "Ojos",
            ],
            [
                'nombre' => "Pelo",
            ],
            [
                'nombre' => "Piel",
            ],
            [
                'nombre' => "Pulmones",
            ],
            [
                'nombre' => "Uñas",
            ],
            [
                'nombre' => "Articulación y/o músculos",
            ],
            [
                'nombre' => "Estómago",
            ],
            [
                'nombre' => "Genitales femeninos",
            ],
        ]);

        DB::table('organo_paciente')->insert([
            [
                'paciente_id' => 1,
                'organo_id' => 1, // GI
                'score_nih' => 'Score_3_1000-1500ml/day',
                'fecha_evaluacion' => '2025-06-20',
                'sintomas_asociados' => json_encode([2, 4, 6, 7]),
                'comentario' => 'Volumen de diarrea elevado con dolor abdominal.',

            ],
            [
                'paciente_id' => 2,
                'organo_id' => 2, // Higado
                'score_nih' => 'SCORE_2_3-6mg/dl',
                'fecha_evaluacion' => '2025-06-20',
                'sintomas_asociados' => json_encode([2, 4, 6, 7]),
                'comentario' => 'Bilirrubina moderadamente elevada.',

            ],

            
        ]);
    }
}
