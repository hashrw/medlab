<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**El sistema deberá almacenar la información sobre los diagnósticos realizados tanto a nivel de síntomas específicos como del estado 
 * general de la enfermedad EICH. Cada diagnóstico general estará asociado a uno o más síntomas evaluados. 
 * Para cada síntoma evaluado, se almacenará: / */

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diagnosticos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medico_id')->constrained('medicos')->onDelete('cascade'); // Relación con Médico

            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('enfermedad_id')->constrained('enfermedads')->onDelete('cascade');
            
            //propiedad derivada de la fecha de trasplante y la fecha de diagnostico
            $table->integer('dias_desde_trasplante')->nullable();
            //puntuación o score_nih del síntoma, atender pregunta-comentario
            $table->string('fase_cie10')->nullable();
            $table->string('tipo_enfermedad')->nullable(); //aguda o crónica
            $table->string('estado_enfermedad')->nullable(); //['Enfermedad estable', 'Enfermedad progresiva', 'Otro', 'Recaída']
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
            $table->string('observaciones')->nullable();          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosticos');
    }
};
