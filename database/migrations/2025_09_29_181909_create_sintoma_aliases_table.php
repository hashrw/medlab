<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sintoma_aliases', function (Blueprint $table) {
            $table->id();

            // Relación con síntoma principal
            $table->foreignId('sintoma_id')
                  ->constrained('sintomas')
                  ->onDelete('cascade');

            // Alias semántico del síntoma (canónico o sinónimo)
            $table->string('alias');

            // Tipo de alias: canonical | synonym | ontology | import | etc.
            $table->string('tipo')->default('canonical');

            // Nota opcional para contexto clínico o técnico
            $table->string('nota')->nullable();

            $table->timestamps();

            // Un alias identifica UN solo concepto clínico (clave para inferencia)
            $table->unique('alias', 'ux_sintoma_aliases_alias_global');

            // Evita duplicar el mismo alias dentro del mismo síntoma (redundante pero correcto)
            $table->unique(['sintoma_id', 'alias'], 'ux_alias_por_sintoma');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sintoma_aliases');
    }
};
