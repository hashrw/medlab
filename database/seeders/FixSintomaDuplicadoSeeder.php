<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixSintomaDuplicadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {

            //  Mapear alias => canónico
            $map = [
                // GI
                ['alias' => 'Dolor en abdomen', 'canon' => 'Dolor abdominal', 'organo_id' => 1],
                ['alias' => 'Dolor abdominal severo', 'canon' => 'Dolor abdominal', 'organo_id' => 1],
                ['alias' => 'Diarrea crónica', 'canon' => 'Diarrea acuosa', 'organo_id' => 1],

                // Ojos
                ['alias' => 'Ojo seco leve', 'canon' => 'Ojo seco', 'organo_id' => 5],
                ['alias' => 'Ojo seco moderado', 'canon' => 'Ojo seco', 'organo_id' => 5],
                ['alias' => 'Ojo seco severo', 'canon' => 'Ojo seco', 'organo_id' => 5],

                // Piel
                ['alias' => 'Cambios escleróticos superficiales', 'canon' => 'Cambios escleróticos de la piel', 'organo_id' => 7],
                ['alias' => 'Cambios escleróticos profundos', 'canon' => 'Cambios escleróticos de la piel', 'organo_id' => 7],

                // Hígado
                ['alias' => 'Bilirrubina elevada', 'canon' => 'Hiperbilirrubinemia', 'organo_id' => 2],
                ['alias' => 'Bilirrubina normal', 'canon' => 'Hiperbilirrubinemia', 'organo_id' => 2],

                // Boca
                ['alias' => 'Úlceras bucales', 'canon' => 'Úlceras orales', 'organo_id' => 4],
            ];

            foreach ($map as $item) {
                // Buscar canónico
                $canonId = DB::table('sintomas')
                    ->where('organo_id', $item['organo_id'])
                    ->where('sintoma', $item['canon'])
                    ->value('id');

                if (!$canonId) {
                    $canonId = DB::table('sintomas')->insertGetId([
                        'sintoma' => $item['canon'],
                        'manif_clinica' => null,
                        'organo_id' => $item['organo_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Buscar alias existente
                $aliasRow = DB::table('sintomas')
                    ->where('organo_id', $item['organo_id'])
                    ->where('sintoma', $item['alias'])
                    ->first();

                if ($aliasRow) {
                    //  Reasignar en paciente_sintoma
                    DB::table('paciente_sintoma')
                        ->where('sintoma_id', $aliasRow->id)
                        ->update(['sintoma_id' => $canonId]);

                    //  Reasignar en organo_paciente (JSON)
                    $ops = DB::table('organo_paciente')->get();
                    foreach ($ops as $row) {
                        if (!$row->sintomas_asociados)
                            continue;
                        $arr = json_decode($row->sintomas_asociados, true);
                        if (!is_array($arr))
                            continue;
                        $changed = false;
                        foreach ($arr as &$sid) {
                            if ((int) $sid === (int) $aliasRow->id) {
                                $sid = $canonId;
                                $changed = true;
                            }
                        }
                        if ($changed) {
                            DB::table('organo_paciente')
                                ->where('id', $row->id)
                                ->update(['sintomas_asociados' => json_encode(array_values(array_unique($arr)))]);
                        }
                    }

                    //  Guardar alias
                    DB::table('sintoma_aliases')->insert([
                        'sintoma_id' => $canonId,
                        'alias' => $item['alias'],
                        'nota' => 'Normalizado automáticamente',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    //  Eliminar duplicado
                    DB::table('sintomas')->where('id', $aliasRow->id)->delete();
                }
            }

            //  Eliminar síntomas que son mediciones puras (usar score_nih en su lugar)
            $eliminar = [
                ['texto' => 'FEV1 60-79%', 'organo_id' => 8],
                ['texto' => 'FEV1 <40%', 'organo_id' => 8],
            ];

            foreach ($eliminar as $del) {
                $row = DB::table('sintomas')
                    ->where('organo_id', $del['organo_id'])
                    ->where('sintoma', $del['texto'])
                    ->first();

                if ($row) {
                    // Borrar de paciente_sintoma
                    DB::table('paciente_sintoma')->where('sintoma_id', $row->id)->delete();

                    // Quitar de organo_paciente
                    $ops = DB::table('organo_paciente')->get();
                    foreach ($ops as $op) {
                        if (!$op->sintomas_asociados)
                            continue;
                        $arr = json_decode($op->sintomas_asociados, true) ?: [];
                        $arr = array_values(array_filter($arr, fn($sid) => (int) $sid !== (int) $row->id));
                        DB::table('organo_paciente')->where('id', $op->id)
                            ->update(['sintomas_asociados' => $arr ? json_encode($arr) : null]);
                    }

                    // Eliminar síntoma
                    DB::table('sintomas')->where('id', $row->id)->delete();
                }
            }
        });
    }
}
