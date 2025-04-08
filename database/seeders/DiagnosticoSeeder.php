<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DiagnosticoSeeder extends Seeder
{
    public function run(): void
    {
        // Insertar datos en la tabla 'diagnosticos'
        DB::table('diagnosticos')->insert([
            [
                'tipo_enfermedad' => 'aguda',
                'f_eval_injerto' => '2022-01-01',
                'estado_injerto' => 'Estable',
                'f_trasplante' => '2002-01-01',
                'dias_desde_trasplante' => 4980,
                'f_electromiografia' => '2022-01-01',
                'f_medulograma' => '2022-01-01',
                'f_espirometria' => '2022-01-01',
                'f_esplenectomia' => '2022-01-01',
                'hipoalbuminemia' => 'No',
                'observaciones' => 'No aplica',
                'estado_id' => 3,
                'comienzo_id' => 2,
                'infeccion_id' => 1
            ],
        ]);

        //Obtener el ID del diagnóstico recién insertado
        $diagnosticoId = DB::table('diagnosticos')->orderBy('id', 'desc')->first()->id;

        //Insertar datos en la tabla 'diagnostico_enfermedad'
        DB::table('diagnostico_enfermedad')->insert([
            [
                'enfermedad_id' => 1, //ID de una enfermedad existente
                'diagnostico_id' => $diagnosticoId,
                'grado_eich' => 'Grado 1',
                'escala_karnofsky' => 'ECOG 2',
            ],
            [
                'enfermedad_id' => 2, //ID de otra enfermedad existente
                'diagnostico_id' => $diagnosticoId,
                'grado_eich' => 'Grado 2',
                'escala_karnofsky' => 'ECOG 3',
            ],
        ]);

        //Insertar datos en la tabla 'diagnostico_sintoma'
        DB::table('diagnostico_sintoma')->insert([
            [
                'fecha_diagnostico' => '2022-01-01',
                'score_nih' => 3.5,
                'sintoma_id' => 1, //ID de un síntoma existente
                'diagnostico_id' => $diagnosticoId,          
            ],
            [
                'fecha_diagnostico' => '2022-01-01',
                'score_nih' => 2.0,
                'sintoma_id' => 2, //ID de otro síntoma existente
                'diagnostico_id' => $diagnosticoId,
            ],
        ]);

        DB::table('diagnostico_paciente')->insert([
            [
                'paciente_id' => 1, //ID de un paciente existente
                'diagnostico_id' => $diagnosticoId,          
            ],
            
        ]);

    }
    
}