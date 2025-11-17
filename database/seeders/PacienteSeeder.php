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

        // Obtener IDs de usuarios que NO tengan paciente asociado
        $userIds = DB::table('users')
            ->whereNull('paciente_id')
            ->pluck('id')
            ->toArray();

        // Obtener síntomas disponibles
        $todosSintomas = DB::table('sintomas')->pluck('id')->toArray();

        foreach ($userIds as $userId) {

            // Crear paciente y vincularlo al user
            $pacienteId = DB::table('pacientes')->insertGetId([
                'user_id'          => $userId,
                'nuhsa'            => 'AN' . $faker->unique()->numerify('##########'),
                'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'peso'             => $faker->randomFloat(2, 50, 120),
                'altura'           => $faker->numberBetween(150, 200),
                'sexo'             => $faker->randomElement(['M', 'F']),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // Actualizar user.paciente_id ← paciente creado
            DB::table('users')
                ->where('id', $userId)
                ->update(['paciente_id' => $pacienteId]);

            // Seleccionar entre 3 y 6 síntomas aleatorios
            $sintomasAleatorios = $faker->randomElements($todosSintomas, rand(3, 6));

            foreach ($sintomasAleatorios as $sintomaId) {
                DB::table('paciente_sintoma')->insert([
                    'paciente_id'      => $pacienteId,
                    'sintoma_id'       => $sintomaId,
                    'fecha_observacion'=> $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                    'activo'           => $faker->boolean(80),
                    'fuente'           => $faker->randomElement(['paciente', 'observación médica', 'monitorización']),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }
}
