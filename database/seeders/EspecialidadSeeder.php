<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspecialidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('especialidads')->insert([
            [
                'nombre' => 'Hematología',
            ],
            [
                'nombre' => 'Oncología',
            ],
            [
                'nombre' => 'Inmunología Clínica',
            ],
            [
                'nombre' => 'Medicina Interna',
            ],
            [
                'nombre' => 'Dermatología',
            ],
            [
                'nombre' => 'Gastroenterología',
            ],
            [
                'nombre' => 'Neumología',
            ],
            [
                'nombre' => 'Oftalmología',
            ],
        ]);
    }
}
