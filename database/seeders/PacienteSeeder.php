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
            echo "PacienteSeeder: no hay users disponibles (users.paciente_id ya informado). Seeder abortado.\n";
            return;
        }

        $todosSintomasIds = DB::table('sintomas')->pluck('id')->map(fn ($v) => (int) $v)->toArray();
        if (empty($todosSintomasIds)) {
            echo "PacienteSeeder: no hay síntomas en la tabla sintomas. Seeder abortado.\n";
            return;
        }

        // Debe existir SintomaAliasSeeder antes que PacienteSeeder
        if (!Schema::hasTable('sintoma_aliases')) {
            echo "PacienteSeeder: falta la tabla sintoma_aliases. Asegura migración/orden de seeders.\n";
            return;
        }

        // Cargamos TODOS los aliases canónicos y su sintoma_id, y los agrupamos por organo_id
        $aliases = DB::table('sintoma_aliases as sa')
            ->join('sintomas as s', 's.id', '=', 'sa.sintoma_id')
            ->where('sa.tipo', 'canonical')
            ->select('sa.alias', 'sa.sintoma_id', 's.organo_id')
            ->orderBy('sa.id')
            ->get();

        if ($aliases->isEmpty()) {
            echo "PacienteSeeder: no hay aliases canónicos en sintoma_aliases. Seeder abortado.\n";
            return;
        }

        $byOrgano = [];
        foreach ($aliases as $row) {
            $oid = (int) $row->organo_id;
            if (!isset($byOrgano[$oid])) $byOrgano[$oid] = [];
            $byOrgano[$oid][] = [
                'alias' => (string) $row->alias,
                'sintoma_id' => (int) $row->sintoma_id,
            ];
        }

        // Helper: escoge N síntomas únicos de un órgano (si existen), de forma determinista-ish (por orden)
        $pickN = function (int $organoId, int $n) use ($byOrgano): array {
            $pool = $byOrgano[$organoId] ?? [];
            if (count($pool) < $n) return [];
            // Tomamos los primeros N (estable, reproducible)
            return array_slice($pool, 0, $n);
        };

        // Helper: inserta paciente + user link + sintomas activos + (opcional) paciente_enfermedad
        $createPaciente = function (int $userId, array $sintomaIds, int $diasDesdeTrasplante, string $caso) use ($faker): ?int {
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

            $sintomaIds = array_values(array_unique(array_map(fn ($v) => (int) $v, $sintomaIds)));

            foreach ($sintomaIds as $sid) {
                DB::table('paciente_sintoma')->insert([
                    'paciente_id' => $pacienteId,
                    'sintoma_id' => $sid,
                    'fecha_observacion' => now()->toDateString(),
                    'activo' => true,
                    'fuente' => 'observación médica',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (Schema::hasTable('paciente_enfermedad')) {
                $fechaTrasplante = now()->subDays($diasDesdeTrasplante)->toDateString();

                // Si no hay enfermedad, no insertamos
                $enfermedadId = DB::table('enfermedads')->value('id');

                if ($enfermedadId) {
                    DB::table('paciente_enfermedad')->insert([
                        'paciente_id' => $pacienteId,
                        'enfermedad_id' => $enfermedadId,
                        'fecha_trasplante' => $fechaTrasplante,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            echo "PacienteSeeder: creado paciente {$pacienteId} para caso {$caso} (user {$userId}).\n";
            return (int) $pacienteId;
        };

        // Mapeo de órganos objetivo: no inventamos; si en tu catálogo GI/Hígado/Piel son otros IDs, se detecta por falta de pool.
        // Ajusta SOLO estos IDs si tu modelo de órganos cambia.
        $ORG_GI = 1;
        $ORG_HIGADO = 2;
        $ORG_PIEL = 7;

        // Definición de casos: solo N síntomas por órgano (no nombres concretos)
        $casos = [
            'GI_parcial_1'   => ['organo_id' => $ORG_GI,     'n' => 2, 'dias' => 35],
            'GI_casi'        => ['organo_id' => $ORG_GI,     'n' => 3, 'dias' => 28],
            'GI_aguda'       => ['organo_id' => $ORG_GI,     'n' => 4, 'dias' => 22],
            'Higado_parcial' => ['organo_id' => $ORG_HIGADO, 'n' => 1, 'dias' => 45],
            'Higado_aguda'   => ['organo_id' => $ORG_HIGADO, 'n' => 3, 'dias' => 25],
            'Piel_parcial'   => ['organo_id' => $ORG_PIEL,   'n' => 1, 'dias' => 40],
            'Piel_aguda'     => ['organo_id' => $ORG_PIEL,   'n' => 2, 'dias' => 18],
        ];

        $idxUser = 0;

        // Crear casos controlados
        foreach ($casos as $nombreCaso => $cfg) {
            if ($idxUser >= count($userIds)) break;

            $organoId = (int) $cfg['organo_id'];
            $n = (int) $cfg['n'];
            $dias = (int) $cfg['dias'];

            $picked = $pickN($organoId, $n);

            if (empty($picked)) {
                $count = isset($byOrgano[$organoId]) ? count($byOrgano[$organoId]) : 0;
                echo "PacienteSeeder: caso {$nombreCaso} sin pool suficiente (organo {$organoId} tiene {$count}, requiere {$n}). Se salta.\n";
                continue;
            }

            $sintomaIds = array_map(fn ($x) => (int) $x['sintoma_id'], $picked);

            $userId = $userIds[$idxUser++];
            $createPaciente($userId, $sintomaIds, $dias, $nombreCaso);
        }

        // Resto aleatorio
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
            $sintomasPaciente = array_values(array_unique(array_map(fn ($v) => (int) $v, $sintomasPaciente)));

            foreach ($sintomasPaciente as $sintomaId) {
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
