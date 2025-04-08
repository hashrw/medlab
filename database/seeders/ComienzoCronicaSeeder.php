<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComienzoCronicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('comienzos')->insert([
            [
                'tipo_comienzo' => "De novo",
            ],
            [
                'tipo_comienzo' => "Progresivo",
            ],
            [
                'tipo_comienzo' => "Quiescente",
            ],
            [
                'tipo_comienzo' => "Segundo o sucesivos episodios",
            ],
        ]);
    }
}
