<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TipoPruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_pruebas')->insert([
            [
                'nombre' => "Analítica",
            ],
            [
                'nombre' => "Histológica",
            ],
            [
                'nombre' => "Funcional / Neurofisiológica",
            ],
            [
                'nombre' => "Microbiológica / Infecciosa",
            ],
            [
                'nombre' => "Prueba de trasplante",
            ],
        ]);
    }
}
