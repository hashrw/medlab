<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('medicamentos')->insert([
            [
                'nombre' => "Paracetamol",
                'miligramos' => 600,
            ],
            [
                'nombre' => "Ibuprofeno",
                'miligramos' => 600,
            ],
            [
                'nombre' => "Rupatadina",
                'miligramos' => 25,
            ],
            [
                'nombre' => "Amoxicilina",
                'miligramos' => 500,
            ],
        ]);
    }
}
