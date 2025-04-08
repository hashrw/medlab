<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class InfeccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('infeccions')->insert([
            [
                'nombre' => "Vírica",
            ],
            [
                'nombre' => "Fúngica",
            ],
            [
                'nombre' => "Bacteriana",
            ],
        ]);
    }
}
