<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('organos')->insert([
            [
                'nombre' => "Tracto gastrointestinal",
            ],
            [
                'nombre' => "Hígado",
            ],
            [
                'nombre' => "Genitales masculinos",
            ],
            [
                'nombre' => "Boca",
            ],
            [
                'nombre' => "Ojos",
            ],
            [
                'nombre' => "Pelo",
            ],
            [
                'nombre' => "Piel",
            ],
            [
                'nombre' => "Pulmones",
            ],
            [
                'nombre' => "Uñas",
            ],
            [
                'nombre' => "Articulación y/o músculos",
            ],
            [
                'nombre' => "Estómago",
            ],
            [
                'nombre' => "Genitales femeninos",
            ],
        ]);

        
    }
}
