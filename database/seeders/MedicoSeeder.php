<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('medicos')->upsert(
            [
                [
                    'user_id' => 1,
                    'residente' => true,
                    'especialidad_id' => 2,
                ],
                [
                    'user_id' => 2,
                    'residente' => true,
                    'especialidad_id' => 2,
                ],
                [
                    'user_id' => 3,
                    'residente' => false,
                    'especialidad_id' => 2,
                ],
            ],
            ['user_id'],                 // clave única
            ['residente', 'especialidad_id'] // campos a actualizar si ya existe
        );
    }
}
