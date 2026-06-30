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

        foreach (['pacientes', 'users', 'sintomas', 'sintoma_aliases', 'organos', 'medicos'] as $t) {
            if (!Schema::hasTable($t)) {
                throw new \RuntimeException("Falta tabla requerida: {$t}");
            }
        }

        $userIds = DB::table('users')
            ->whereNull('paciente_id')
            ->where('tipo_usuario_id', 2)
            ->orderBy('id')
            ->limit(3)
            ->pluck('id')
            ->map(fn($v) => (int) $v)
            ->toArray();

        if (count($userIds) < 3) {
            throw new \RuntimeException(
                "Se necesitan al menos 3 users PACIENTE con users.paciente_id NULL. Encontrados: " . count($userIds)
            );
        }

        if (!DB::table('medicos')->where('id', 1)->exists()) {
            throw new \RuntimeException("PacienteSeeder: no existe medico_id=1.");
        }

        $ORG_GI = 'Tracto gastrointestinal';
        $ORG_HIG = 'Hígado';
        $ORG_PIEL = 'Piel';

        $oid = function (string $organoNombre): int {
            $id = DB::table('organos')->where('nombre', $organoNombre)->value('id');

            if (!$id) {
                throw new \RuntimeException("Órgano no encontrado: {$organoNombre}");
            }

            return (int) $id;
        };

        $OID_GI = $oid($ORG_GI);
        $OID_HIG = $oid($ORG_HIG);
        $OID_PIEL = $oid($ORG_PIEL);

        $slug = function (string $txt): string {
            $txt = trim($txt);
            $txt = preg_replace('/\s+/u', ' ', $txt);
            $ascii = \Illuminate\Support\Str::ascii($txt);
            return \Illuminate\Support\Str::slug($ascii, '_');
        };

        $canon = function (string $textoSintoma, int $organoId) use ($slug): string {
            return 'o' . $organoId . '_' . $slug($textoSintoma);
        };

        $sidByCanonicalAlias = function (string $canonicalAlias, int $organoId): int {
            $row = DB::table('sintoma_aliases as sa')
                ->join('sintomas as s', 's.id', '=', 'sa.sintoma_id')
                ->where('sa.alias', $canonicalAlias)
                ->where('sa.tipo', 'canonical')
                ->where('s.organo_id', $organoId)
                ->select('sa.sintoma_id')
                ->first();

            if (!$row) {
                throw new \RuntimeException("Alias canónico no encontrado: {$canonicalAlias}");
            }

            return (int) $row->sintoma_id;
        };

        $sidFromText = function (string $textoSintoma, int $organoId) use ($canon, $sidByCanonicalAlias): int {
            return $sidByCanonicalAlias($canon($textoSintoma, $organoId), $organoId);
        };

        $hasOrganoPaciente = Schema::hasTable('organo_paciente');
        $hasPacienteEnfermedad = Schema::hasTable('paciente_enfermedad');

        $enfermedadId = null;
        if ($hasPacienteEnfermedad && Schema::hasTable('enfermedads')) {
            $enfermedadId = DB::table('enfermedads')->value('id');
        }

        $insertPaciente = function (int $userId, int $medicoId, string $nuhsa, array $sintomaIds, array $organScores, int $diasDesdeTrasplante, string $tag) use ($faker, $hasOrganoPaciente, $hasPacienteEnfermedad, $enfermedadId): int {
            $u = DB::table('users')->select('id', 'tipo_usuario_id', 'paciente_id')->where('id', $userId)->first();

            if (!$u) {
                throw new \RuntimeException("PacienteSeeder: user {$userId} no existe.");
            }

            if ((int) $u->tipo_usuario_id !== 2) {
                throw new \RuntimeException("PacienteSeeder: user {$userId} no es paciente.");
            }

            if (!is_null($u->paciente_id)) {
                throw new \RuntimeException("PacienteSeeder: user {$userId} ya tiene paciente asociado.");
            }

            if (!DB::table('medicos')->where('id', $medicoId)->exists()) {
                throw new \RuntimeException("PacienteSeeder: médico {$medicoId} no existe.");
            }

            $pacienteId = DB::table('pacientes')->insertGetId([
                'medico_id' => $medicoId,
                'nuhsa' => $nuhsa,
                'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'peso' => $faker->randomFloat(2, 50, 120),
                'altura' => $faker->numberBetween(150, 200),
                'sexo' => $faker->randomElement(['M', 'F']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->where('id', $userId)->update(['paciente_id' => $pacienteId]);

            foreach (array_values(array_unique($sintomaIds)) as $sid) {
                DB::table('paciente_sintoma')->insert([
                    'paciente_id' => $pacienteId,
                    'sintoma_id' => (int) $sid,
                    'fecha_observacion' => now()->toDateString(),
                    'activo' => true,
                    'fuente' => "seed_controlado:{$tag}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

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
                            'comentario' => "seed_controlado:{$tag}",
                            'sintomas_asociados' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }

            if ($hasPacienteEnfermedad && $enfermedadId) {
                DB::table('paciente_enfermedad')->insert([
                    'paciente_id' => $pacienteId,
                    'enfermedad_id' => (int) $enfermedadId,
                    'fecha_trasplante' => now()->subDays($diasDesdeTrasplante)->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return (int) $pacienteId;
        };

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
                'tag' => 'SEVERA',
                'nuhsa' => 'AN0876037616',
                'sintomas' => array_merge($sx_hig, $sx_gi_severa),
                'scores' => [$OID_HIG => 2, $OID_GI => 2],
                'dias' => 25,
            ],
            [
                'tag' => 'MODERADA',
                'nuhsa' => 'AN0876037717',
                'sintomas' => array_merge($sx_gi_moderada, $sx_piel),
                'scores' => [$OID_GI => 1, $OID_PIEL => 1],
                'dias' => 35,
            ],
            [
                'tag' => 'LEVE',
                'nuhsa' => 'AN0876037818',
                'sintomas' => $sx_piel,
                'scores' => [$OID_PIEL => 1],
                'dias' => 20,
            ],
        ];

        foreach ($casos as $i => $c) {
            $pid = $insertPaciente(
                $userIds[$i],
                1,
                $c['nuhsa'],
                $c['sintomas'],
                $c['scores'],
                $c['dias'],
                $c['tag']
            );

            echo "PacienteSeeder: creado paciente {$pid} ({$c['tag']}) NUHSA {$c['nuhsa']}\n";
        }
    }
}