<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicamento_tratamiento', function (Blueprint $table) {
            $table->id();

            $table->date('fecha_ini_linea')->nullable();
            $table->date('fecha_fin_linea')->nullable();
            $table->date('fecha_resp_linea')->nullable();

            $table->text('observaciones')->nullable();
            $table->integer('tomas')->nullable();

            // FKs NO nulas (pivot consistente)
            $table->foreignId('medicamento_id')->constrained()->onDelete('cascade');
            $table->foreignId('tratamiento_id')->constrained()->onDelete('cascade');

            $table->timestamps();

            // Evitar duplicados de medicamento dentro del mismo tratamiento
            $table->unique(['tratamiento_id', 'medicamento_id']);

            // Índices útiles
            $table->index('fecha_ini_linea');
            $table->index('fecha_fin_linea');
            $table->index('fecha_resp_linea');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicamento_tratamiento');
    }
};