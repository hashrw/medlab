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
            $table->string('tipo_enfermedad')->nullable(); //aguda o crónica
            $table->enum('origen', ['manual', 'inferido'])->nullable();
            $table->string('observaciones')->nullable();
            $table->foreignId('regla_decision_id')->nullable()->constrained()->onDelete('set null');
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
