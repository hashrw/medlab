<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicamentoSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Sistémicos
            ['nombre' => 'Methylprednisolone', 'miligramos' => 40],
            ['nombre' => 'Prednisone', 'miligramos' => 5],

            // Tópicos (conceptual, NO farmacocinético)
            ['nombre' => 'Esteroides tópicos', 'miligramos' => 0],
        ];

        foreach ($items as $it) {
            DB::table('medicamentos')->updateOrInsert(
                [
                    'nombre' => $it['nombre'],
                    'miligramos' => $it['miligramos']
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
