<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class OrigenDiagnosticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('origens')->insert([
            [
                'origen' => "Inferido por el sistema",
            ],
            [
                'origen' => "Diagnóstico manual",
            ],

        ]);
    }
}
