<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReglaDecision;
use App\Models\Sintoma;

class ReglaDecisionSeeder extends Seeder
{
    public function run(): void
    {
        // Nombres exactos de órganos (DEBEN coincidir con organos.nombre)
        $ORG_GI   = 'Tracto gastrointestinal';
        $ORG_HIG  = 'Hígado';
        $ORG_PIEL = 'Piel';

        // organo_id según SintomaSeeder
        $OID_GI   = 1;
        $OID_HIG  = 2;
        $OID_PIEL = 7;

        // Helper: resuelve IDs por texto exacto de síntoma + órgano
        $sid = function (string $textoSintoma, int $organoId): int {
            $s = Sintoma::query()
                ->where('sintoma', $textoSintoma)
                ->where('organo_id', $organoId)
                ->first();

            if (!$s) {
                throw new \RuntimeException(
                    "Síntoma no encontrado: '{$textoSintoma}' (organo_id={$organoId})"
                );
            }

            return (int) $s->id;
        };

        /*
        |--------------------------------------------------------------------------
        | REGLA SEVERA
        |--------------------------------------------------------------------------
        */
        ReglaDecision::updateOrCreate(
            ['nombre' => 'EICH severa (GI score 2 + hígado score 2)'],
            [
                'prioridad' => 5,
                'tipo_recomendacion' => 'diagnostico',
                'activo' => true,
                'condiciones' => [
                    $ORG_HIG => [
                        'score' => 2,
                        'sintomas' => [
                            $sid('Hiperbilirrubinemia', $OID_HIG),
                            $sid('ALT elevada', $OID_HIG),
                            $sid('Fosfatasa alcalina elevada', $OID_HIG),
                        ],
                    ],
                    $ORG_GI => [
                        'score' => 2,
                        'sintomas' => [
                            $sid('Diarrea con sangre', $OID_GI),
                            $sid('Dolor abdominal', $OID_GI),
                            $sid('Vómitos', $OID_GI),
                            $sid('Náuseas', $OID_GI),
                        ],
                    ],
                ],
                'diagnostico' => $this->diagnosticoBase([
                    'tipo_enfermedad' => 'EICH',
                    'estado_injerto' => 'critico',
                    'grado_eich' => 'severa',
                    'observaciones' =>
                        'EICH severa con afectación gastrointestinal y hepática.',
                ]),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | REGLA MODERADA
        |--------------------------------------------------------------------------
        */
        ReglaDecision::updateOrCreate(
            ['nombre' => 'EICH moderada (GI score 1 + piel score 1)'],
            [
                'prioridad' => 20,
                'tipo_recomendacion' => 'diagnostico',
                'activo' => true,
                'condiciones' => [
                    $ORG_GI => [
                        'score' => 1,
                        'sintomas' => [
                            $sid('Diarrea acuosa', $OID_GI),
                            $sid('Dolor abdominal', $OID_GI),
                            $sid('Anorexia', $OID_GI),
                        ],
                    ],
                    $ORG_PIEL => [
                        'score' => 1,
                        'sintomas' => [
                            $sid('Exantema maculopapular', $OID_PIEL),
                        ],
                    ],
                ],
                'diagnostico' => $this->diagnosticoBase([
                    'tipo_enfermedad' => 'EICH',
                    'estado_injerto' => 'inestable',
                    'grado_eich' => 'moderada',
                    'observaciones' =>
                        'EICH moderada con afectación cutánea y gastrointestinal.',
                ]),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | REGLA LEVE
        |--------------------------------------------------------------------------
        */
        ReglaDecision::updateOrCreate(
            ['nombre' => 'EICH leve (piel score 1)'],
            [
                'prioridad' => 30,
                'tipo_recomendacion' => 'diagnostico',
                'activo' => true,
                'condiciones' => [
                    $ORG_PIEL => [
                        'score' => 1,
                        'sintomas' => [
                            $sid('Exantema maculopapular', $OID_PIEL),
                        ],
                    ],
                ],
                'diagnostico' => $this->diagnosticoBase([
                    'tipo_enfermedad' => 'EICH',
                    'estado_injerto' => 'estable',
                    'grado_eich' => 'leve',
                    'observaciones' =>
                        'EICH leve con afectación cutánea mínima.',
                ]),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | FALLBACK (última, prioridad máxima)
        |--------------------------------------------------------------------------
        */
        ReglaDecision::updateOrCreate(
            ['nombre' => 'Sin criterios suficientes de EICH'],
            [
                'prioridad' => 9999,
                'tipo_recomendacion' => 'diagnostico',
                'activo' => true,
                'condiciones' => [],
                'diagnostico' => $this->diagnosticoBase([
                    'tipo_enfermedad' => 'EICH',
                    'estado_injerto' => 'estable',
                    'grado_eich' => 'no_concluyente',
                    'observaciones' =>
                        'No se cumplen criterios suficientes para inferir EICH.',
                ]),
            ]
        );
    }

    private function diagnosticoBase(array $override = []): array
    {
        return array_merge([
            'fecha_diagnostico' => null,
            'tipo_enfermedad' => null,
            'estado_injerto' => null,
            'observaciones' => null,
            'grado_eich' => null,
            'escala_karnofsky' => null,
            'regla_decision_id' => null,
            'estado_id' => null,
            'comienzo_id' => null,
            'infeccion_id' => null,
            'origen_id' => null, // sobrescrito por el servicio
        ], $override);
    }
}
