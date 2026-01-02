<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');

        $userIds = DB::table('users')
            ->whereNull('paciente_id')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        if (empty($userIds)) {
            dump('PacienteSeeder: no hay users disponibles (users.paciente_id ya informado). Seeder abortado.');
            return;
        }

        $todosSintomas = DB::table('sintomas')->select('id', 'alias', 'nombre')->get();
        if ($todosSintomas->isEmpty()) {
            dump('PacienteSeeder: no hay síntomas en la tabla sintomas. Seeder abortado.');
            return;
        }

        // Resolver ID de síntoma por alias o nombre (exacto, y si no, por LIKE).
        $sid = function (string $aliasOrName) use ($todosSintomas): ?int {
            $s = $todosSintomas->firstWhere('alias', $aliasOrName)
              ?? $todosSintomas->firstWhere('nombre', $aliasOrName);

            if ($s) return (int) $s->id;

            $needle = mb_strtolower($aliasOrName);
            $sLike = $todosSintomas->first(function ($row) use ($needle) {
                $a = mb_strtolower((string) ($row->alias ?? ''));
                $n = mb_strtolower((string) ($row->nombre ?? ''));
                return str_contains($a, $needle) || str_contains($n, $needle);
            });

            return $sLike ? (int) $sLike->id : null;
        };

        // Casos controlados para probar:
        // Ajusta los aliases a los que existan realmente en tu tabla sintomas.
        $casos = [
            // Parcial GI: falta un síntoma clave (no debería cerrar regla completa)
            'GI_parcial_1' => [
                'sintomas' => ['gastro_diarrea', 'gastro_dolor_abdominal'],
                'dias_desde_trasplante' => 35,
            ],
            // Casi GI: le falta 1 síntoma (casi dispara)
            'GI_casi' => [
                'sintomas' => ['gastro_diarrea', 'gastro_nauseas', 'gastro_dolor_abdominal'],
                'dias_desde_trasplante' => 28,
            ],
            // Aguda GI completa: set completo (debería disparar si la regla usa estos)
            'GI_aguda' => [
                'sintomas' => ['gastro_diarrea', 'gastro_nauseas', 'gastro_dolor_abdominal', 'gastro_vomitos'],
                'dias_desde_trasplante' => 22,
            ],

            // Parcial Piel
            'Piel_parcial' => [
                'sintomas' => ['piel_eritema'],
                'dias_desde_trasplante' => 40,
            ],
            // Aguda Piel (ejemplo)
            'Piel_aguda' => [
                'sintomas' => ['piel_eritema', 'piel_exantema', 'piel_prurito'],
                'dias_desde_trasplante' => 18,
            ],

            // Parcial Hígado
            'Higado_parcial' => [
                'sintomas' => ['higado_ictericia'],
                'dias_desde_trasplante' => 45,
            ],
            // Aguda Hígado (ejemplo)
            'Higado_aguda' => [
                'sintomas' => ['higado_ictericia', 'higado_bilirrubina_alta'],
                'dias_desde_trasplante' => 25,
            ],
        ];

        // Convertimos casos a listas de IDs reales
        $casosIds = [];
        foreach ($casos as $key => $cfg) {
            $ids = [];
            foreach ($cfg['sintomas'] as $a) {
                $id = $sid($a);
                if ($id) $ids[] = $id;
                else dump("PacienteSeeder: síntoma no encontrado para alias/nombre '{$a}' (caso {$key}). Se omite.");
            }
            $ids = array_values(array_unique($ids));
            $casosIds[$key] = [
                'sintomas' => $ids,
                'dias_desde_trasplante' => (int) $cfg['dias_desde_trasplante'],
            ];
        }

        // Lista total de IDs para random
        $todosSintomasIds = $todosSintomas->pluck('id')->map(fn($v) => (int) $v)->toArray();

        $idxUser = 0;

        foreach ($casosIds as $nombreCaso => $cfg) {
            if ($idxUser >= count($userIds)) break; // no más users disponibles

            // Si el caso quedó sin síntomas (porque no existían aliases), saltamos
            if (empty($cfg['sintomas'])) {
                continue;
            }

            $userId = $userIds[$idxUser++];

            $pacienteId = DB::table('pacientes')->insertGetId([
                'nuhsa' => 'AN' . $faker->unique()->numerify('##########'),
                'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'peso' => $faker->randomFloat(2, 50, 120),
                'altura' => $faker->numberBetween(150, 200),
                'sexo' => $faker->randomElement(['M', 'F']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->where('id', $userId)->update(['paciente_id' => $pacienteId]);

            // Insertar síntomas activos (caso controlado)
            foreach ($cfg['sintomas'] as $sintomaId) {
                DB::table('paciente_sintoma')->insert([
                    'paciente_id' => $pacienteId,
                    'sintoma_id' => $sintomaId,
                    'fecha_observacion' => now()->toDateString(),
                    'activo' => true,
                    'fuente' => 'observación médica',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Opcional: si existe paciente_enfermedad, fijamos fecha_trasplante para simular aguda
            if (Schema::hasTable('paciente_enfermedad')) {
                $fechaTrasplante = now()->subDays($cfg['dias_desde_trasplante'])->toDateString();

                // Si tu pivot requiere más campos obligatorios, añade aquí.
                DB::table('paciente_enfermedad')->insert([
                    'paciente_id' => $pacienteId,
                    'enfermedad_id' => DB::table('enfermedads')->value('id'), // primera enfermedad
                    'fecha_trasplante' => $fechaTrasplante,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Marca en consola para que sepas qué paciente corresponde a qué caso
            dump("PacienteSeeder: creado paciente {$pacienteId} para caso {$nombreCaso} (user {$userId}).");
        }

        // Resto de users: pacientes aleatorios como antes
        for (; $idxUser < count($userIds); $idxUser++) {
            $userId = $userIds[$idxUser];

            $pacienteId = DB::table('pacientes')->insertGetId([
                'nuhsa' => 'AN' . $faker->unique()->numerify('##########'),
                'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'peso' => $faker->randomFloat(2, 50, 120),
                'altura' => $faker->numberBetween(150, 200),
                'sexo' => $faker->randomElement(['M', 'F']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->where('id', $userId)->update(['paciente_id' => $pacienteId]);

            $sintomasPaciente = $faker->randomElements($todosSintomasIds, rand(3, 6));

            foreach ($sintomasPaciente as $sintomaId) {
                DB::table('paciente_sintoma')->insert([
                    'paciente_id' => $pacienteId,
                    'sintoma_id' => (int) $sintomaId,
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
