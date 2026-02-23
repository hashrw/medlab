<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tratamientos', function (Blueprint $table) {
            $table->id();

            // Fuente de verdad del estado global
            $table->boolean('activo')->default(false); // antes nullable

            $table->string('tratamiento')->nullable();
            $table->date('fecha_asignacion')->nullable();

            // NUEVO: fechas clínicas globales (sin renombrar nada)
            $table->date('fecha_inicio')->nullable(); // inicio real del tratamiento
            $table->date('fecha_cierre')->nullable(); // cierre global decidido explícitamente

            $table->text('descripcion')->nullable();

            // Relación con diagnóstico (tu modelo ya lo usa)
            $table->foreignId('diagnostico_id')->nullable()
                ->constrained('diagnosticos')
                ->nullOnDelete();

            $table->foreignId('medico_id')->constrained()->onDelete('cascade');

            // RECOMENDADO: evitar borrar tratamientos si se borra paciente
            $table->foreignId('paciente_id')->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();

            // Índices útiles
            $table->index('activo');
            $table->index('fecha_asignacion');
            $table->index('fecha_inicio');
            $table->index('fecha_cierre');
            $table->index('diagnostico_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tratamientos');
    }
};