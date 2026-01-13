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

        // Requisitos mínimos
        foreach (['pacientes', 'users', 'sintomas', 'sintoma_aliases', 'organos'] as $t) {
            if (!Schema::hasTable($t)) {
                throw new \RuntimeException("Falta tabla requerida: {$t}");
            }
        }

        // 5 users libres para vincular pacientes
        $userIds = DB::table('users')
            ->whereNull('paciente_id')
            ->orderBy('id')
            ->limit(5)
            ->pluck('id')
            ->map(fn ($v) => (int) $v)
            ->toArray();

        if (count($userIds) < 5) {
            throw new \RuntimeException("Se necesitan al menos 5 users con users.paciente_id NULL. Encontrados: " . count($userIds));
        }

        // Nombres exactos de órganos (deben coincidir con organos.nombre)
        $ORG_GI = 'Tracto gastrointestinal';
        $ORG_HIG = 'Hígado';
        $ORG_PIEL = 'Piel';

        $oid = function (string $organoNombre): int {
            $id = DB::table('organos')->where('nombre', $organoNombre)->value('id');
            if (!$id) {
                throw new \RuntimeException("Órgano no encontrado en organos.nombre: '{$organoNombre}'");
            }
            return (int) $id;
        };

        $OID_GI = $oid($ORG_GI);
        $OID_HIG = $oid($ORG_HIG);
        $OID_PIEL = $oid($ORG_PIEL);

        // Construye alias canónico como en ReglaDecisionSeeder: o{organo_id}_{slug(Str::ascii(texto))}
        $slug = function (string $txt): string {
            $txt = trim($txt);
            $txt = preg_replace('/\s+/u', ' ', $txt);
            $ascii = \Illuminate\Support\Str::ascii($txt);
            return \Illuminate\Support\Str::slug($ascii, '_');
        };

        $canon = function (string $textoSintoma, int $organoId) use ($slug): string {
            return 'o' . $organoId . '_' . $slug($textoSintoma);
        };

        // Resuelve sintoma_id a partir del alias canónico (validando órgano)
        $sidByCanonicalAlias = function (string $canonicalAlias, int $organoId): int {
            $row = DB::table('sintoma_aliases as sa')
                ->join('sintomas as s', 's.id', '=', 'sa.sintoma_id')
                ->where('sa.alias', $canonicalAlias)
                ->where('sa.tipo', 'canonical')
                ->where('s.organo_id', $organoId)
                ->select('sa.sintoma_id')
                ->first();

            if (!$row) {
                throw new \RuntimeException("Alias canónico no encontrado o no pertenece al órgano: '{$canonicalAlias}' (organo_id={$organoId})");
            }

            return (int) $row->sintoma_id;
        };

        $sidFromText = function (string $textoSintoma, int $organoId) use ($canon, $sidByCanonicalAlias): int {
            $alias = $canon($textoSintoma, $organoId);
            return $sidByCanonicalAlias($alias, $organoId);
        };

        // Si tu motor lee scores desde organo_paciente, los insertamos. Si no existe la tabla, no bloquea.
        $hasOrganoPaciente = Schema::hasTable('organo_paciente');

        // Si tu proyecto usa paciente_enfermedad para fecha_trasplante, lo insertamos si existe.
        $hasPacienteEnfermedad = Schema::hasTable('paciente_enfermedad');

        $enfermedadId = null;
        if ($hasPacienteEnfermedad && Schema::hasTable('enfermedads')) {
            $enfermedadId = DB::table('enfermedads')->value('id');
        }

        $insertPaciente = function (int $userId, array $sintomaIds, array $organScores, int $diasDesdeTrasplante, string $tag)
            use ($faker, $hasOrganoPaciente, $hasPacienteEnfermedad, $enfermedadId)
        : int {
            $pacienteId = DB::table('pacientes')->insertGetId([
                'nuhsa' => 'DIA' . $faker->unique()->numerify('##########'),
                'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'peso' => $faker->randomFloat(2, 50, 120),
                'altura' => $faker->numberBetween(150, 200),
                'sexo' => $faker->randomElement(['M', 'F']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->where('id', $userId)->update(['paciente_id' => $pacienteId]);

            // paciente_sintoma (síntomas activos)
            foreach (array_values(array_unique($sintomaIds)) as $sid) {
                DB::table('paciente_sintoma')->insert([
                    'paciente_id' => $pacienteId,
                    'sintoma_id' => (int) $sid,
                    'fecha_observacion' => now()->toDateString(),
                    'activo' => true,
                    'fuente' => "seed_diana:{$tag}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // organo_paciente (scores NIH por órgano) si existe
            if ($hasOrganoPaciente) {
                foreach ($organScores as $organoId => $scoreNih) {
                    DB::table('organo_paciente')->updateOrInsert(
                        [
                            'paciente_id' => $pacienteId,
                            'organo_id' => (int) $organoId,
                        ],
                        [
                            'score_nih' => (int) $scoreNih,
                            'fecha_evaluacion' => now()->toDateString(),
                            'comentario' => "seed_diana:{$tag}",
                            'sintomas_asociados' => null,
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }
            }

            // paciente_enfermedad (fecha_trasplante) si existe y hay enfermedad
            if ($hasPacienteEnfermedad && $enfermedadId) {
                $fechaTrasplante = now()->subDays($diasDesdeTrasplante)->toDateString();

                DB::table('paciente_enfermedad')->insert([
                    'paciente_id' => $pacienteId,
                    'enfermedad_id' => (int) $enfermedadId,
                    'fecha_trasplante' => $fechaTrasplante,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return (int) $pacienteId;
        };

        /*
         | Reglas (según tu ReglaDecisionSeeder):
         | - Severa: GI score 2 + hígado score 2 + síntomas GI (4) + hígado (3)
         | - Moderada: GI score 1 + piel score 1 + síntomas GI (3) + piel (1)
         | - Leve: piel score 1 + síntoma piel (1)
         |
         | Creamos 5 pacientes:
         |   - 2 severa
         |   - 2 moderada
         |   - 1 leve
         */

        // Síntomas exactos de tus reglas
        $sx_hig = [
            $sidFromText('Hiperbilirrubinemia', $OID_HIG),
            $sidFromText('ALT elevada', $OID_HIG),
            $sidFromText('Fosfatasa alcalina elevada', $OID_HIG),
        ];

        $sx_gi_severa = [
            $sidFromText('Diarrea con sangre', $OID_GI),
            $sidFromText('Dolor abdominal', $OID_GI),
            $sidFromText('Vómitos', $OID_GI),
            $sidFromText('Náuseas', $OID_GI),
        ];

        $sx_gi_moderada = [
            $sidFromText('Diarrea acuosa', $OID_GI),
            $sidFromText('Dolor abdominal', $OID_GI),
            $sidFromText('Anorexia', $OID_GI),
        ];

        $sx_piel = [
            $sidFromText('Exantema maculopapular', $OID_PIEL),
        ];

        $casos = [
            [
                'tag' => 'SEVERA_1',
                'sintomas' => array_merge($sx_hig, $sx_gi_severa),
                'scores' => [$OID_HIG => 2, $OID_GI => 2],
                'dias' => 25,
            ],
            [
                'tag' => 'SEVERA_2',
                'sintomas' => array_merge($sx_hig, $sx_gi_severa),
                'scores' => [$OID_HIG => 2, $OID_GI => 2],
                'dias' => 30,
            ],
            [
                'tag' => 'MODERADA_1',
                'sintomas' => array_merge($sx_gi_moderada, $sx_piel),
                'scores' => [$OID_GI => 1, $OID_PIEL => 1],
                'dias' => 35,
            ],
            [
                'tag' => 'MODERADA_2',
                'sintomas' => array_merge($sx_gi_moderada, $sx_piel),
                'scores' => [$OID_GI => 1, $OID_PIEL => 1],
                'dias' => 40,
            ],
            [
                'tag' => 'LEVE_1',
                'sintomas' => $sx_piel,
                'scores' => [$OID_PIEL => 1],
                'dias' => 20,
            ],
        ];

        // Crear
        foreach ($casos as $i => $c) {
            $userId = $userIds[$i];

            $pid = $insertPaciente(
                $userId,
                $c['sintomas'],
                $c['scores'],
                $c['dias'],
                $c['tag']
            );

            echo "PacientesDianaInferenciaSeeder: creado paciente {$pid} ({$c['tag']}) enlazado a user {$userId}\n";
        }
    }
}
