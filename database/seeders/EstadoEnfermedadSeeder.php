<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoEnfermedadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estados')->insert([
            [
                'estado' => "Enfermedad Progresiva",
            ],
            [
                'estado' => "Otro",
            ],
            [
                'estado' => "Enfermedad Estable",
            ],
            [
                'estado' => "Recaída",
            ],
        ]);
    }
}
