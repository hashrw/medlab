<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiagnosticoSeeder extends Seeder
{
    public function run(): void
    {
        // Insertar en la tabla 'diagnosticos'
        DB::table('diagnosticos')->insert([
            [
                'fecha_diagnostico' => '2022-01-01',
                'tipo_enfermedad' => 'aguda',
                'origen_id' => 2, // 'manual' si fue ingresado por un médico
                'dias_desde_trasplante' => 984,
                'estado_injerto' => 'Estable', //quizá añadir en una otra tabla como con la especialidad del medico.
                'regla_decision_id' => 1, // Debe existir una regla con ID 1 o ajustarlo
                'estado_id' => 3,
                'comienzo_id' => 2,
                'infeccion_id' => 1,
                'escala_karnofsky' => 'ECOG 2',
                'grado_eich' => 'Grado 1',
                'observaciones' => 'No aplica',
            ],
        ]);

        // Obtener el ID del diagnóstico recién insertado
        $diagnosticoId = DB::table('diagnosticos')->orderBy('id', 'desc')->first()->id;

        DB::table('diagnostico_paciente')->insert([
            'diagnostico_id' => $diagnosticoId,
            'paciente_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Relación con síntomas
        DB::table('diagnostico_sintoma')->insert([
            [
                'fecha_diagnostico' => '2022-01-01',
                'score_nih' => 3.5,
                'sintoma_id' => 1,
                'diagnostico_id' => $diagnosticoId,
            ],
            [
                'fecha_diagnostico' => '2022-01-01',
                'score_nih' => 2.0,
                'sintoma_id' => 2,
                'diagnostico_id' => $diagnosticoId,
            ],
        ]);

    }
}
