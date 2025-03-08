<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class DiagnosticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('diagnosticos')->insert([
            [
                'medico_id' => 1,
                'paciente_id' => 1,
                'enfermedad_id' => 1,
                'dias_desde_trasplante' => 189,
                'tipo_enfermedad' => 'aguda',
                'estado_enfermedad' => 'Enfermedad estable',
                'comienzo_cronica' => 'progresivo',
                'escala_karnofsky' => 'ECOG 2',
                'estado_injerto' => 'Estable',
                'tipo_infeccion' => 'vírica',
                'f_hospitalizacion' => '2022-01-01',
                'f_electromiografia' => '2022-01-01',
                'f_eval_injerto' => '2022-01-01',
                'f_medulograma' => '2022-01-01',
                'f_espirometria' => '2022-01-01',
                'f_esplenectomia' => '2022-01-01',
                'hipoalbuminemia' => 'No',
                'observaciones' => 'No aplica',
            ],
        ]);
        /* //propiedad derivada de la fecha de trasplante y la fecha de diagnostico
            $table->integer('dias_desde_trasplante')->nullable();
            //puntuación o score_nih del síntoma, atender pregunta-comentario
            $table->string('fase_cie10')->nullable();
            $table->string('tipo_enfermedad')->nullable(); //aguda o crónica
            $table->string(column: 'estado_enfermedad')->nullable(); //['Enfermedad estable', 'Enfermedad progresiva', 'Otro', 'Recaída']
            $table->string('fase_enfermedad')->nullable(); //(grado 1, grado 2, grado 3 y grado 4)
            $table->string('comienzo_cronica')->nullable(); //(de novo, progresivo, quiescente o segundo sucesivos)
            //Fase crónica: 'Leve', 'Moderada', 'Grave']
            //['Positivo', 'Negativo']
            $table->string('escala_karnofsky')->nullable();
            // ['ECOG 1', 'ECOG 2', 'ECOG 3', 'ECOG 4']
            $table->string('tipo_infeccion')->nullable(); //['Vírica', 'Bacteriana', 'Fúngica']
            $table->string('estado_injerto')->nullable(); //['Estable', 'Pobre']

            //fechas en tabla Pruebas¿?
            $table->date('f_hospitalizacion')->nullable();
            $table->date('f_eval_injerto')->nullable();
            $table->date('f_electromiografia')->nullable();
            $table->date('f_medulograma')->nullable();
            $table->date('f_espirometria')->nullable();
            $table->date('f_esplenectomia')->nullable();

            $table->string('hipoalbuminemia')->nullable();  
            $table->string(column: 'observaciones')->nullable();  */
    }
}
