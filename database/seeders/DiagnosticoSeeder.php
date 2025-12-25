<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiagnosticoSeeder extends Seeder
{
    public function run(): void
    {
        // 1) IDs base (deben existir)
        $origenManualId = DB::table('origens')->where('origen', 'manual')->value('id');
        $reglaId        = DB::table('regla_decisions')->orderBy('id')->value('id'); // primera regla
        $estadoId       = DB::table('estados')->orderBy('id')->value('id');
        $comienzoId     = DB::table('comienzos')->orderBy('id')->value('id');
        $infeccionId    = DB::table('infeccions')->orderBy('id')->value('id');

        $pacienteId     = DB::table('pacientes')->orderBy('id')->value('id');

        $sintoma1Id     = DB::table('sintomas')->orderBy('id')->value('id');
        $sintoma2Id     = DB::table('sintomas')->orderBy('id')->skip(1)->value('id');

        if (!$pacienteId || !$sintoma1Id || !$origenManualId) {
            dump("DiagnosticoSeeder: faltan datos base (paciente/sintoma/origen). Abortando.");
            return;
        }

        // 2) Insertar diagnóstico (AHORA con paciente_id en la tabla diagnosticos)
        $diagnosticoId = DB::table('diagnosticos')->insertGetId([
            'paciente_id'       => $pacienteId,          // <- CLAVE DEL CAMBIO (1:N)
            'fecha_diagnostico' => '2022-01-01',
            'tipo_enfermedad'   => 'aguda',
            'origen_id'         => $origenManualId,
            'estado_injerto'    => 'Estable',
            'regla_decision_id' => $reglaId,             // opcional (puede ser null)
            'estado_id'         => $estadoId,            // opcional (puede ser null)
            'comienzo_id'       => $comienzoId,          // opcional (puede ser null)
            'infeccion_id'      => $infeccionId,         // opcional (puede ser null)
            'escala_karnofsky'  => 'ECOG 2',
            'grado_eich'        => 'Grado 1',
            'observaciones'     => 'No aplica',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // 3) Eliminado: diagnostico_paciente (ya no se usa en el modelo 1:N)
        // DB::table('diagnostico_paciente')->insert([...]);

        // 4) Pivot diagnostico_sintoma
        DB::table('diagnostico_sintoma')->insert([
            [
                'diagnostico_id'    => $diagnosticoId,
                'sintoma_id'        => $sintoma1Id,
                'fecha_diagnostico' => '2022-01-01',
                'score_nih'         => 3, // NIH entero (coherente con tu validación max:3)
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'diagnostico_id'    => $diagnosticoId,
                'sintoma_id'        => $sintoma2Id,
                'fecha_diagnostico' => '2022-01-01',
                'score_nih'         => 2,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
    }
}
