<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')
                ->constrained('pacientes')
                ->cascadeOnDelete();

            $table->foreignId('medico_id')
                ->nullable()
                ->constrained('medicos')
                ->nullOnDelete();

            // Solicitud (paciente) -> null, Cita real (médico) -> datetime
            $table->dateTime('fecha_hora')->nullable();

            // Estado de la solicitud/cita
            $table->string('estado', 20)->default('pendiente');

            // Motivos (texto simple v1, refactor a tabla más adelante)
            $table->string('motivo', 120)->nullable();
            $table->text('motivo_detalle')->nullable();

            // Preferencia del paciente
            $table->dateTime('preferencia_fecha_hora')->nullable();

            // Gestión por médico
            $table->text('comentario_medico')->nullable();
            $table->timestamp('respondida_at')->nullable();

            $table->timestamps();

            // Índices útiles para bandeja del médico y listados
            $table->index(['medico_id', 'estado']);
            $table->index(['paciente_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
