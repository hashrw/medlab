<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');

        // Obtener IDs de usuarios que NO tengan paciente asociado
        $userIds = DB::table('users')
            ->whereNull('paciente_id')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        // Obtener síntomas disponibles
        $todosSintomas = DB::table('sintomas')->pluck('id')->toArray();

        if (empty($todosSintomas)) {
            dump('PacienteSeeder: no hay síntomas en la tabla sintomas. Seeder abortado.');
            return;
        }

        // Síntomas objetivo para que la regla GI pueda disparar (IDs 1,2,3)
        $sintomasObjetivo = [1, 2, 3];

        // Filtramos por si alguno de esos IDs no existe realmente
        $sintomasObjetivoDisponibles = array_values(
            array_intersect($sintomasObjetivo, $todosSintomas)
        );

        $primerPacienteConfigurado = false;

        foreach ($userIds as $userId) {

            // Crear paciente y vincularlo al user
            $pacienteId = DB::table('pacientes')->insertGetId([
                'nuhsa' => 'AN' . $faker->unique()->numerify('##########'),
                'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'peso' => $faker->randomFloat(2, 50, 120),
                'altura' => $faker->numberBetween(150, 200),
                'sexo' => $faker->randomElement(['M', 'F']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Actualizar user.paciente_id ← paciente creado
            DB::table('users')
                ->where('id', $userId)
                ->update(['paciente_id' => $pacienteId]);

            // Determinar qué síntomas asignar a este paciente
            if (!$primerPacienteConfigurado && !empty($sintomasObjetivoDisponibles)) {
                // Primer paciente "clínico" preparado para la regla GI:
                // asegura que tenga al menos 1,2,3 (si existen) y opcionalmente alguno más
                $sintomasAleatoriosExtra = $faker->randomElements(
                    $todosSintomas,
                    max(0, rand(0, 3))
                );

                $sintomasPaciente = array_values(array_unique(array_merge(
                    $sintomasObjetivoDisponibles,
                    $sintomasAleatoriosExtra
                )));

                $primerPacienteConfigurado = true;
            } else {
                // Resto de pacientes: lógica aleatoria normal
                $sintomasPaciente = $faker->randomElements(
                    $todosSintomas,
                    rand(3, 6)
                );
            }

            foreach ($sintomasPaciente as $sintomaId) {
                DB::table('paciente_sintoma')->insert([
                    'paciente_id' => $pacienteId,
                    'sintoma_id' => $sintomaId,
                    'fecha_observacion' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                    // Por definición del dominio: esta tabla guarda solo síntomas activos
                    'activo' => true,
                    'fuente' => $faker->randomElement(['paciente', 'observación médica', 'monitorización']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
