<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiagnosticoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. IDs necesarios que deben existir previamente
        $origenManualId = DB::table('origens')->where('origen', 'manual')->value('id');
        $reglaId = DB::table('regla_decisions')->value('id');   // primera regla
        $estadoId = DB::table('estados')->value('id');
        $comienzoId = DB::table('comienzos')->value('id');
        $infeccionId = DB::table('infeccions')->value('id');
        $pacienteId = DB::table('pacientes')->value('id');
        $sintoma1Id = DB::table('sintomas')->skip(0)->value('id');
        $sintoma2Id = DB::table('sintomas')->skip(1)->value('id');

        // Evitar fallos si faltan tablas/datos base
        if (!$pacienteId || !$sintoma1Id) {
            dump("DiagnosticoSeeder: faltan datos base. Abortando.");
            return;
        }

        // 2. Insertar diagnóstico
        $diagnosticoId = DB::table('diagnosticos')->insertGetId([
            'fecha_diagnostico' => '2022-01-01',
            'tipo_enfermedad' => 'aguda',
            'origen_id' => $origenManualId,
            'estado_injerto' => 'Estable',
            'regla_decision_id' => $reglaId,
            'estado_id' => $estadoId,
            'comienzo_id' => $comienzoId,
            'infeccion_id' => $infeccionId,
            'escala_karnofsky' => 'ECOG 2',
            'grado_eich' => 'Grado 1',
            'observaciones' => 'No aplica',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Relación diagnóstico ↔ paciente
        DB::table('diagnostico_paciente')->insert([
            'diagnostico_id' => $diagnosticoId,
            'paciente_id' => $pacienteId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Pivot diagnóstico_sintoma (SIN campo origen)
        DB::table('diagnostico_sintoma')->insert([
            [
                'diagnostico_id' => $diagnosticoId,
                'sintoma_id' => $sintoma1Id,
                'fecha_diagnostico' => '2022-01-01',
                'score_nih' => 3.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'diagnostico_id' => $diagnosticoId,
                'sintoma_id' => $sintoma2Id,
                'fecha_diagnostico' => '2022-01-01',
                'score_nih' => 2.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
