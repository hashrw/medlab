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
            //puntuación o score_nih del síntoma, atender pregunta-comentario
            $table->string('tipo_enfermedad')->nullable(); //aguda o crónica
            $table->date('f_eval_injerto')->nullable();
            $table->string('estado_injerto')->nullable(); //['Estable', 'Pobre']
            $table->date('f_trasplante')->nullable();
            //propiedad derivada de la fecha de trasplante y la fecha de diagnostico
            $table->integer('dias_desde_trasplante')->nullable();
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
