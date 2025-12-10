<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganoSeeder extends Seeder
{
    public function run(): void
    {
        $organos = [
            "Tracto gastrointestinal",
            "Hígado",
            "Genitales masculinos",
            "Boca",
            "Ojos",
            "Pelo",
            "Piel",
            "Pulmones",
            "Uñas",
            "Articulación y/o músculos",
            "Estómago",
            "Genitales femeninos",
        ];

        foreach ($organos as $nombre) {
            DB::table('organos')->updateOrInsert(
                ['nombre' => $nombre], // condición
                [
                    'nombre' => $nombre,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
