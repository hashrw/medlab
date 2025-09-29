<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**El sistema deberá almacenar la información sobre los diagnósticos realizados tanto a nivel de síntomas específicos como del estado
 * general de la enfermedad EICH. Cada diagnóstico general estará asociado a uno o más síntomas evaluados.
 * Para cada síntoma evaluado, se almacenará: / */

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diagnosticos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_diagnostico')->nullable();
            $table->string('tipo_enfermedad')->nullable(); //aguda o crónica
            $table->enum('origen', allowed: ['manual', 'inferido'])->nullable();
            $table->string('estado_injerto')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('grado_eich')->nullable(); //grado específico a tipo trasplante//(grado 1, grado 2, grado 3 y grado 4 y grados EICH crónica: 'Leve', 'Moderada', 'Grave']
            $table->string('escala_karnofsky')->nullable(); //estado fase crónica general // ['ECOG 1', 'ECOG 2', 'ECOG 3', 'ECOG 4']
            $table->foreignId('regla_decision_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /*
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosticos');
    }
};
