<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReglaDecisionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('regla_decisions')->insert([

            // 1. EICH aguda gastrointestinal grave
            [
                'condiciones' => json_encode([
                    'Tracto gastrointestinal' => [
                        'score' => '3',
                        'sintomas' => [1, 2, 3] // diarrea con sangre, acuosa, dolor abdominal
                    ]
                ]),
                'diagnostico' => json_encode([
                    'nombre' => 'EICH aguda gastrointestinal severa',
                    'descripcion' => 'Diarrea >1000ml/día con sangrado y dolor abdominal',
                    'origen' => 'Inferido',
                ]),
                'tipo_recomendacion' => 'Alerta clínica',
            ],

            // 2. EICH hepática moderada
            [
                'condiciones' => json_encode([
                    'Hígado' => [
                        'score' => '2',
                        'sintomas' => [6, 15] // hiperbilirrubinemia, ALT elevada
                    ]
                ]),
                'diagnostico' => json_encode([
                    'nombre' => 'EICH hepática moderada',
                    'descripcion' => 'Bilirrubina 3–6 mg/dl con alteración enzimática',
                    'origen' => 'Inferido',
                ]),
                'tipo_recomendacion' => 'Seguimiento intensivo',
            ],

            // 3. EICH ocular leve
            [
                'condiciones' => json_encode([
                    'Ojos' => [
                        'score' => '1',
                        'sintomas' => [8] // ojo seco
                    ]
                ]),
                'diagnostico' => json_encode([
                    'nombre' => 'EICH ocular leve',
                    'descripcion' => 'Sequedad ocular leve sin impacto funcional',
                    'origen' => 'Inferido',
                ]),
                'tipo_recomendacion' => 'Control ambulatorio',
            ],

            // 4. EICH pulmonar severa
            [
                'condiciones' => json_encode([
                    'Pulmones' => [
                        'score' => '3',
                        'sintomas' => [26, 27] // disnea, tos seca
                    ]
                ]),
                'diagnostico' => json_encode([
                    'nombre' => 'EICH pulmonar severa',
                    'descripcion' => 'Compromiso pulmonar grave con disnea marcada',
                    'origen' => 'Inferido',
                ]),
                'tipo_recomendacion' => 'Ingreso hospitalario',
            ],

            // 5. EICH cutánea moderada
            [
                'condiciones' => json_encode([
                    'Piel' => [
                        'score' => '2',
                        'sintomas' => [7, 19] // exantema maculopapular, cambios escleróticos
                    ]
                ]),
                'diagnostico' => json_encode([
                    'nombre' => 'EICH cutánea moderada',
                    'descripcion' => 'Lesiones cutáneas difusas con cambios escleróticos superficiales',
                    'origen' => 'Inferido',
                ]),
                'tipo_recomendacion' => 'Tratamiento inmunosupresor',
            ],
        ]);
    }
}
