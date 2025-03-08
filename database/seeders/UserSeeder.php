<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => "Medico1",
                'email' => "medico1@medico.com",
                'apellidos' => "Primero",
                'telefono' => "624524122",
                'tipo_usuario_id' => 1,
                'password' => Hash::make('merci'),
                
            ],
            [
                'name' => "Pacientel",
                'email' => "paciente1@gmail.com",
                'apellidos' => "Tercero",
                'telefono' => "624524124",
                'tipo_usuario_id' => 2,
                'password' => Hash::make('12345678'),
            ],
        ]);
    }
}
