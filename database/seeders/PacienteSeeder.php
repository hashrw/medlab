<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('es_ES');

        // Obtener los IDs de los usuarios que son pacientes (tipo_usuario_id = 2)
        $userIds = DB::table('users')->where('tipo_usuario_id', 2)->pluck('id')->toArray();

        // Crear menos pacientes para que no pete el seeder. (tabla relacionada con enfermedad y tratamiento)
        foreach ($userIds as $userId) {
            DB::table('pacientes')->insert([
                'nuhsa' => 'AN' . $faker->unique()->numerify('##########'),
                'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'peso' => $faker->randomFloat(2, 50, 120),
                'altura' => $faker->numberBetween(150, 200),
                'sexo' => $faker->randomElement(['M', 'F']),
                'user_id' => $userId, // Asignar el user_id correspondiente,
                'enfermedad_id' => $faker->numberBetween(1, 3),
                'tratamiento_id' => 1,
            ]);
        }
    }
}