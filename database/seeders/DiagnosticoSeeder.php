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
                'tipo_enfermedad' => 'aguda',
                'origen_id' => 2, // 'manual' si fue ingresado por un médico
                'observaciones' => 'No aplica',
                'regla_decision_id' => 1, // Debe existir una regla con ID 1 o ajustarlo
                'estado_id' => 3,
                'comienzo_id' => 2,
                'infeccion_id' => 1
            ],
        ]);

        // Obtener el ID del diagnóstico recién insertado
        $diagnosticoId = DB::table('diagnosticos')->orderBy('id', 'desc')->first()->id;

        // Relación con enfermedades
        DB::table('diagnostico_enfermedad')->insert([
            [
                'enfermedad_id' => 1,
                'diagnostico_id' => $diagnosticoId,
                'grado_eich' => 'Grado 1',
                'escala_karnofsky' => 'ECOG 2',
            ],
            [
                'enfermedad_id' => 2,
                'diagnostico_id' => $diagnosticoId,
                'grado_eich' => 'Grado 2',
                'escala_karnofsky' => 'ECOG 3',
            ],
        ]);

        // Relación con síntomas
        DB::table('diagnostico_sintoma')->insert([
            [
                'fecha_diagnostico' => '2022-01-01',
                'score_nih' => 3.5,
                'sintoma_id' => 1,
                'diagnostico_id' => $diagnosticoId,
                'origen' => 'Inferido',
            ],
            [
                'fecha_diagnostico' => '2022-01-01',
                'score_nih' => 2.0,
                'sintoma_id' => 2,
                'diagnostico_id' => $diagnosticoId,
                'origen' => 'Inferido',
            ],
        ]);

    }
}
