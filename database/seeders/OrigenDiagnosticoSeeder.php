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
        // Evitar duplicar registros si ya existen
        $existenteManual = DB::table('origens')->where('origen', 'Manual')->exists();
        $existenteInferido = DB::table('origens')->where('origen', 'Inferido')->exists();

        if (!$existenteManual) {
            DB::table('origens')->insert([
                'origen' => 'Manual',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!$existenteInferido) {
            DB::table('origens')->insert([
                'origen' => 'Inferido',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
