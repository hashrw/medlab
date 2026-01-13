<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicamento_aliases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medicamento_id')
                ->constrained('medicamentos')
                ->onDelete('cascade');

            // Alias normalizado (ej: "prednisona", "etanercept", "ic")
            $table->string('alias')->unique();

            // Tipo: "canonical" | "synonym" (string por ahora, como has pedido)
            $table->string('tipo')->default('canonical');

            $table->timestamps();

            $table->index('medicamento_id');
            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicamento_aliases');
    }
};
