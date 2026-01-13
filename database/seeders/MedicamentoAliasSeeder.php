<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MedicamentoAlias;
use Illuminate\Support\Str;

class MedicamentoAliasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('medicamento_aliases')->truncate();

        $mid = function (string $nombre, int $mg): int {
            $id = DB::table('medicamentos')
                ->where('nombre', $nombre)
                ->where('miligramos', $mg)
                ->value('id');

            if (!$id) {
                throw new \RuntimeException("Medicamento no encontrado: {$nombre} {$mg}mg");
            }

            return (int) $id;
        };

        $canon = fn(string $t) => Str::slug(Str::ascii(trim($t)), '_');

        $items = [
            // =========================
            // METILPREDNISOLONA 40 mg
            // =========================
            [
                'nombre' => 'Methylprednisolone',
                'mg' => 40,
                'alias' => 'metilprednisolona_40mg',
                'tipo' => 'canonical',
            ],
            [
                'nombre' => 'Methylprednisolone',
                'mg' => 40,
                'alias' => 'metilprednisolona',
                'tipo' => 'synonym',
            ],
            [
                'nombre' => 'Methylprednisolone',
                'mg' => 40,
                'alias' => 'metil/prednisona',
                'tipo' => 'synonym',
            ],

            // =========================
            // PREDNISONA 5 mg
            // =========================
            [
                'nombre' => 'Prednisone',
                'mg' => 5,
                'alias' => 'prednisona_5mg',
                'tipo' => 'canonical',
            ],
            [
                'nombre' => 'Prednisone',
                'mg' => 5,
                'alias' => 'prednisona',
                'tipo' => 'synonym',
            ],

            // =========================
            // ESTEROIDES TÓPICOS (CONCEPTO CLÍNICO)
            // =========================
            [
                'nombre' => 'Esteroides tópicos',
                'mg' => 0,
                'alias' => 'esteroides_topicos',
                'tipo' => 'canonical',
            ],
            [
                'nombre' => 'Esteroides tópicos',
                'mg' => 0,
                'alias' => 'topical_corticosteroids',
                'tipo' => 'synonym',
            ],
        ];

        foreach ($items as $it) {
            MedicamentoAlias::updateOrCreate(
                ['alias' => $canon($it['alias'])],
                [
                    'medicamento_id' => $mid($it['nombre'], (int) $it['mg']),
                    'tipo' => $it['tipo'],
                ]
            );
        }
    }
}

