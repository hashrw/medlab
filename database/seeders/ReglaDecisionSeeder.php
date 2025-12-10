<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReglaDecisionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('regla_decisions')->insert([

            // 1. EICH aguda gastrointestinal severa
            [
                'nombre' => 'EICH aguda gastrointestinal severa',
                'condiciones' => json_encode([
                    'Tracto gastrointestinal' => [
                        'score' => '3',
                        'sintomas' => [1, 2, 3], // diarrea con sangre, acuosa, dolor abdominal
                    ],
                ]),
                'diagnostico' => json_encode([
                    'tipo_enfermedad' => 'aguda',
                    'estado_injerto' => 'Compromiso gastrointestinal severo',
                    'grado_eich' => 'Grado 3',
                    'escala_karnofsky' => 'ECOG 3',
                    'observaciones' => 'Diarrea >1000 ml/día con sangrado y dolor abdominal.',
                ]),
                'tipo_recomendacion' => 'Alerta clínica',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 2. EICH hepática moderada
            [
                'nombre' => 'EICH hepática moderada',
                'condiciones' => json_encode([
                    'Hígado' => [
                        'score' => '2',
                        'sintomas' => [6, 19], // hiperbilirrubinemia, ALT elevada
                    ],
                ]),
                'diagnostico' => json_encode([
                    'tipo_enfermedad' => 'crónica',
                    'estado_injerto' => 'Compromiso hepático moderado',
                    'grado_eich' => 'Moderada',
                    'escala_karnofsky' => 'ECOG 2',
                    'observaciones' => 'Bilirrubina 3–6 mg/dl con alteración enzimática.',
                ]),
                'tipo_recomendacion' => 'Seguimiento intensivo',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 3. EICH ocular leve
            [
                'nombre' => 'EICH ocular leve',
                'condiciones' => json_encode([
                    'Ojos' => [
                        'score' => '1',
                        'sintomas' => [8], // ojo seco
                    ],
                ]),
                'diagnostico' => json_encode([
                    'tipo_enfermedad' => 'crónica',
                    'estado_injerto' => 'Compromiso ocular leve',
                    'grado_eich' => 'Leve',
                    'escala_karnofsky' => 'ECOG 1',
                    'observaciones' => 'Sequedad ocular leve sin impacto funcional relevante.',
                ]),
                'tipo_recomendacion' => 'Control ambulatorio',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 4. EICH pulmonar severa
            [
                'nombre' => 'EICH pulmonar severa',
                'condiciones' => json_encode([
                    'Pulmones' => [
                        'score' => '3',
                        'sintomas' => [29, 30], // disnea, tos seca
                    ],
                ]),
                'diagnostico' => json_encode([
                    'tipo_enfermedad' => 'crónica',
                    'estado_injerto' => 'Compromiso pulmonar severo',
                    'grado_eich' => 'Grave',
                    'escala_karnofsky' => 'ECOG 3',
                    'observaciones' => 'Compromiso pulmonar grave con disnea marcada y tos seca persistente.',
                ]),
                'tipo_recomendacion' => 'Ingreso hospitalario',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 5. EICH cutánea moderada
            [
                'nombre' => 'EICH cutánea moderada',
                'condiciones' => json_encode([
                    'Piel' => [
                        'score' => '2',
                        'sintomas' => [7, 19], // exantema maculopapular, cambios escleróticos
                    ],
                ]),
                'diagnostico' => json_encode([
                    'tipo_enfermedad' => 'crónica',
                    'estado_injerto' => 'Compromiso cutáneo moderado',
                    'grado_eich' => 'Moderada',
                    'escala_karnofsky' => 'ECOG 2',
                    'observaciones' => 'Lesiones cutáneas difusas con cambios escleróticos superficiales.',
                ]),
                'tipo_recomendacion' => 'Tratamiento inmunosupresor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
