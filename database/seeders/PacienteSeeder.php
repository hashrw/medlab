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

        // Obtener IDs de síntomas disponibles en la BD
        $todosSintomas = DB::table('sintomas')->pluck('id')->toArray();

        foreach ($userIds as $index => $userId) {
            $pacienteId = DB::table('pacientes')->insertGetId([
                'nuhsa' => 'AN' . $faker->unique()->numerify('##########'),
                'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'peso' => $faker->randomFloat(2, 50, 120),
                'altura' => $faker->numberBetween(150, 200),
                'sexo' => $faker->randomElement(['M', 'F']),
                'user_id' => $userId,
            ]);

            // Seleccionar entre 3 y 6 síntomas aleatorios para cada paciente
            $sintomasAleatorios = $faker->randomElements($todosSintomas, rand(3, 6));

            foreach ($sintomasAleatorios as $sintomaId) {
                DB::table('paciente_sintoma')->insert([
                    'paciente_id' => $pacienteId,
                    'sintoma_id' => $sintomaId,
                    'fecha_observacion' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                    'activo' => $faker->boolean(80), // 80% activos
                    'fuente' => $faker->randomElement(['paciente', 'observación médica', 'monitorización']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
