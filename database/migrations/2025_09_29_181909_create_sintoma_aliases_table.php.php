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

            // 🔹 Relación con síntoma principal
            $table->foreignId('sintoma_id')
                  ->constrained('sintomas')
                  ->onDelete('cascade');

            // 🔹 Texto del alias
            $table->string('alias');

            // 🔹 Nota opcional para contexto (ej. “leve/moderado/severo”)
            $table->string('nota')->nullable();

            $table->timestamps();

            // 🔹 Evita duplicados de alias para el mismo síntoma
            $table->unique(['sintoma_id', 'alias'], 'ux_alias_por_sintoma');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sintoma_aliases');
    }
};
