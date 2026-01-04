<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ReglaDecision;

class ReglaDecisionSeeder extends Seeder
{
    public function run(): void
    {
        // Nombres exactos de órganos (DEBEN coincidir con organos.nombre)
        $ORG_GI = 'Tracto gastrointestinal';
        $ORG_HIG = 'Hígado';
        $ORG_PIEL = 'Piel';

        // Resolver organo_id dinámico (sin hardcode)
        $oid = function (string $organoNombre): int {
            $id = DB::table('organos')->where('nombre', $organoNombre)->value('id');
            if (!$id) {
                throw new \RuntimeException("Órgano no encontrado en organos.nombre: '{$organoNombre}'");
            }
            return (int) $id;
        };

        $OID_GI = $oid($ORG_GI);
        $OID_HIG = $oid($ORG_HIG);
        $OID_PIEL = $oid($ORG_PIEL);

        // Helper: devuelve alias canónico a partir del texto de sintomas.sintoma:
        // alias = o{organo_id}_{slug(Str::ascii(sintoma))}
        $canon = function (string $textoSintoma, int $organoId): string {
            $base = $this->slug($textoSintoma);
            return 'o' . $organoId . '_' . $base;
        };

        // Helper: valida que el alias canónico exista en sintoma_aliases y pertenece al órgano
        $a = function (string $textoSintoma, int $organoId) use ($canon): string {
            $alias = $canon($textoSintoma, $organoId);

            $exists = DB::table('sintoma_aliases')
                ->join('sintomas', 'sintomas.id', '=', 'sintoma_aliases.sintoma_id')
                ->where('sintoma_aliases.alias', $alias)
                ->where('sintoma_aliases.tipo', 'canonical')
                ->where('sintomas.organo_id', $organoId)
                ->exists();

            if (!$exists) {
                throw new \RuntimeException(
                    "Alias canónico no encontrado: '{$alias}' (texto='{$textoSintoma}', organo_id={$organoId}). " .
                    "Revisa que sintomas.sintoma coincida y que SintomaAliasSeeder se haya ejecutado."
                );
            }

            return $alias;
        };

        /*
        |--------------------------------------------------------------------------
        | REGLA SEVERA
        |--------------------------------------------------------------------------
        | Nota: mantenemos score exacto como en tu versión (2).
        | Si quieres >=2, cambia 'score' por 'score_min' en regla + servicio.
        */
        ReglaDecision::updateOrCreate(
            ['nombre' => 'EICH severa (GI score 2 + hígado score 2)'],
            [
                'prioridad' => 5,
                'tipo_recomendacion' => 'Diagnóstico clínico compatible con EICH aguda severa. Requiere valoración médica inmediata y manejo especializado.',
                'activo' => true,
                'condiciones' => [
                    $ORG_HIG => [
                        'score' => 2,
                        'sintomas' => [
                            $a('Hiperbilirrubinemia', $OID_HIG),
                            $a('ALT elevada', $OID_HIG),
                            $a('Fosfatasa alcalina elevada', $OID_HIG),
                        ],
                    ],
                    $ORG_GI => [
                        'score' => 2,
                        'sintomas' => [
                            $a('Diarrea con sangre', $OID_GI),
                            $a('Dolor abdominal', $OID_GI),
                            $a('Vómitos', $OID_GI),
                            $a('Náuseas', $OID_GI),
                        ],
                    ],
                ],
                'diagnostico' => $this->diagnosticoBase([
                    'tipo_enfermedad' => 'EICH Aguda',
                    'estado_injerto' => 'critico',
                    'grado_eich' => 'severa',
                    'observaciones' => 'EICH severa con afectación gastrointestinal y hepática.',
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
                'tipo_recomendacion' => 'Diagnóstico clínico compatible con EICH aguda moderada. Requiere seguimiento estrecho y ajuste terapéutico.',
                'activo' => true,
                'condiciones' => [
                    $ORG_GI => [
                        'score' => 1,
                        'sintomas' => [
                            $a('Diarrea acuosa', $OID_GI),
                            $a('Dolor abdominal', $OID_GI),
                            $a('Anorexia', $OID_GI),
                        ],
                    ],
                    $ORG_PIEL => [
                        'score' => 1,
                        'sintomas' => [
                            $a('Exantema maculopapular', $OID_PIEL),
                        ],
                    ],
                ],
                'diagnostico' => $this->diagnosticoBase([
                    'tipo_enfermedad' => 'EICH Aguda ',
                    'estado_injerto' => 'inestable',
                    'grado_eich' => 'moderada',
                    'observaciones' => 'EICH moderada con afectación cutánea y gastrointestinal.',
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
                            $a('Exantema maculopapular', $OID_PIEL),
                        ],
                    ],
                ],
                'diagnostico' => $this->diagnosticoBase([
                    'tipo_recomendacion' => 'Diagnóstico clínico compatible con EICH aguda leve. Requiere control clínico y monitorización evolutiva.',
                    'estado_injerto' => 'estable',
                    'grado_eich' => 'leve',
                    'observaciones' => 'EICH leve con afectación cutánea mínima.',
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
                'tipo_recomendacion' => 'No se identifican criterios clínicos suficientes para establecer diagnóstico de EICH en este momento.',
                'activo' => true,
                'condiciones' => [],
                'diagnostico' => $this->diagnosticoBase([
                    'tipo_enfermedad' => 'EICH Aguda',
                    'estado_injerto' => 'estable',
                    'grado_eich' => 'no_concluyente',
                    'observaciones' => 'No se cumplen criterios suficientes para inferir EICH.',
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

    private function slug(string $txt): string
    {
        $txt = trim($txt);
        $txt = preg_replace('/\s+/u', ' ', $txt);
        $ascii = \Illuminate\Support\Str::ascii($txt);
        return \Illuminate\Support\Str::slug($ascii, '_');
    }
}
