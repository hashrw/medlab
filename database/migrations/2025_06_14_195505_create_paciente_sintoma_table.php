<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacienteSintomaTable extends Migration
{
    public function up(): void
    {
        Schema::create('paciente_sintoma', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('sintoma_id')->constrained()->onDelete('cascade')->nullable();

            $table->date('fecha_observacion')->nullable();
            $table->boolean('activo')->default(true)->nullable();
            $table->string('fuente')->nullable()->nullable(); // ej: "paciente", "observación médica", "monitorización"

            $table->timestamps();
            $table->unique(['paciente_id', 'sintoma_id']); // evita duplicados activos por paciente
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paciente_sintoma');
    }
}
