<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnfermedadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('enfermedads')->insert([
            [
                'tipo_trasplante' => 'alogénico emparentado',
                'nombre_enfermedad' => 'EICH aguda',
                'fecha_trasplante' => '2001-05-30 10:15:00',    
                'origen_trasplante' => 'médula ósea',
                'identidad_hla' => 'disparidad clase I',
                'tipo_acondicionamiento' => 'de intensidad reducida',
                'seropositividad_donante' => '+',
                'seropositividad_receptor' => '-',
                'paciente_id' => 1,
            ]
    ]);
    
    }
}
