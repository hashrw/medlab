<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('es_ES');

        // Crear médicos
        for ($i = 1; $i <= 3; $i++) { // Crear 3 médicos
            DB::table('users')->insert([
                'name' => "Medico$i",
                'email' => "medico$i@medico.com",
                'apellidos' => $faker->lastName,
                'telefono' => $faker->numerify('6#########'), // Número de teléfono español
                'tipo_usuario_id' => 1, // Médico
                'password' => Hash::make('merci'), // Contraseña por defecto
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Crear pacientes
        for ($i = 1; $i <= 20; $i++) { // Crear 20 pacientes
            DB::table('users')->insert([
                'name' => $faker->firstName,
                'email' => $faker->unique()->safeEmail,
                'apellidos' => $faker->lastName,
                'telefono' => $faker->numerify('6#########'), // Número de teléfono español
                'tipo_usuario_id' => 2, // Paciente
                'password' => Hash::make('merci'), // Contraseña por defecto
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}