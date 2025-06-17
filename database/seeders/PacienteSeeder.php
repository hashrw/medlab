<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PacienteSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_ES');

        // Obtener los IDs de los usuarios que son pacientes
        $userIds = DB::table('users')->where('tipo_usuario_id', 2)->pluck('id')->toArray();

        foreach ($userIds as $index => $userId) {
            $pacienteId = DB::table('pacientes')->insertGetId([
                'nuhsa' => 'AN' . $faker->unique()->numerify('##########'),
                'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'peso' => $faker->randomFloat(2, 50, 120),
                'altura' => $faker->numberBetween(150, 200),
                'sexo' => $faker->randomElement(['M', 'F']),
                'user_id' => $userId,
            ]);

            // Relación con enfermedad
            DB::table('paciente_enfermedad')->insert([
                'paciente_id' => $pacienteId,
                'enfermedad_id' => 1,
            ]);

            // Relación con tratamiento
            DB::table('paciente_tratamiento')->insert([
                'paciente_id' => $pacienteId,
                'tratamiento_id' => 1,
            ]);

            // Relación fija con síntomas 1, 3, 5, 7, 9
            $sintomasFijos = [1, 3, 5, 7, 9];
            foreach ($sintomasFijos as $sintomaId) {
                DB::table('paciente_sintoma')->insert([
                    'paciente_id' => $pacienteId,
                    'sintoma_id' => $sintomaId,
                    'fecha_observacion' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                    'activo' => true,
                    'fuente' => $faker->randomElement(['paciente', 'observación médica', 'monitorización']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
