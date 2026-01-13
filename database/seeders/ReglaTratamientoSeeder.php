<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReglaTratamiento;

class ReglaTratamientoSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | EICH AGUDA – LEVE
        |--------------------------------------------------------------------------
        | Primera línea: soporte / tópicos
        */
        ReglaTratamiento::updateOrCreate(
            ['nombre' => 'EICH aguda leve → soporte / tópico'],
            [
                'prioridad' => 30,
                'activo' => true,
                'condiciones' => [
                    'tipo_enfermedad' => 'aguda',
                    'grado_eich' => 'leve',
                ],
                'acciones' => [
                    'tratamiento' => [
                        'tratamiento' => 'EICH aguda leve (plan inferido)',
                        'descripcion' => 'Manejo de soporte y tratamiento tópico según afectación.',
                    ],
                    'lineas' => [
                        [
                            'orden' => 1,
                            'nombre_linea' => 'Primera línea',
                            'medicamento_alias' => 'esteroides_topicos',
                            'fecha_ini_linea' => 'AUTO_TODAY',
                            'duracion_linea' => 14,
                            'tomas' => null,
                            'observaciones' => 'Tópico según órgano (piel/mucosas).',
                        ],
                    ],
                ],
                'fuente' => 'Ontología EICH – Aguda 1ª línea',
                'observaciones' => 'Regla clínica v1.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | EICH AGUDA – MODERADA
        |--------------------------------------------------------------------------
        | Primera línea: esteroide sistémico (SIN inferir IC)
        */
        ReglaTratamiento::updateOrCreate(
            ['nombre' => 'EICH aguda moderada → esteroide sistémico'],
            [
                'prioridad' => 20,
                'activo' => true,
                'condiciones' => [
                    'tipo_enfermedad' => 'aguda',
                    'grado_eich' => 'moderada',
                ],
                'acciones' => [
                    'tratamiento' => [
                        'tratamiento' => 'EICH aguda moderada (plan inferido)',
                        'descripcion' => 'Primera línea con esteroide sistémico.',
                    ],
                    'lineas' => [
                        [
                            'orden' => 1,
                            'nombre_linea' => 'Primera línea',
                            'medicamento_alias' => 'metilprednisolona_40mg',
                            'fecha_ini_linea' => 'AUTO_TODAY',
                            'duracion_linea' => 14,
                            'tomas' => 1,
                            'observaciones' => 'Esteroide sistémico según protocolo.',
                        ],
                    ],
                ],
                'fuente' => 'Ontología EICH – Aguda 1ª línea',
                'observaciones' => 'IC NO se infiere automáticamente.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | EICH AGUDA – SEVERA
        |--------------------------------------------------------------------------
        | Primera línea intensiva + sugerencia de segunda línea
        */
        ReglaTratamiento::updateOrCreate(
            ['nombre' => 'EICH aguda severa → esteroide sistémico + posible 2ª línea'],
            [
                'prioridad' => 10,
                'activo' => true,
                'condiciones' => [
                    'tipo_enfermedad' => 'aguda',
                    'grado_eich' => 'severa',
                ],
                'acciones' => [
                    'tratamiento' => [
                        'tratamiento' => 'EICH aguda severa (plan inferido)',
                        'descripcion' => 'Primera línea intensiva. Segunda línea solo como sugerencia.',
                    ],
                    'lineas' => [
                        [
                            'orden' => 1,
                            'nombre_linea' => 'Primera línea',
                            'medicamento_alias' => 'metilprednisolona_40mg',
                            'fecha_ini_linea' => 'AUTO_TODAY',
                            'duracion_linea' => 14,
                            'tomas' => 1,
                            'observaciones' => 'Seguimiento estrecho.',
                        ],
                    ],
                ],
                'fuente' => 'Ontología EICH – Aguda',
                'observaciones' => 'La 2ª línea puede marcarse como “propuesta”.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */
        ReglaTratamiento::updateOrCreate(
            ['nombre' => 'Fallback → sin plan de tratamiento'],
            [
                'prioridad' => 9999,
                'activo' => true,
                'condiciones' => [],
                'acciones' => [
                    'tratamiento' => [
                        'tratamiento' => null,
                        'descripcion' => 'No existe una regla de tratamiento aplicable.',
                    ],
                    'lineas' => [],
                ],
                'fuente' => null,
                'observaciones' => null,
            ]
        );
    }
}
