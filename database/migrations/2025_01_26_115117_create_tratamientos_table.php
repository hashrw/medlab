<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tratamientos', function (Blueprint $table) {
            $table->id();
            
            $table->string('tratamiento');
            $table->date('fecha_asignacion');
            $table->text('descripcion')->nullable();
            //se calcula a partir de la fecha de inicio de la 1ª línea de tratamiento y la fecha de fin de la última línea de tratamiento asignada.
            $table->integer('duracion_trat');
            $table->foreignId('medico_id')->constrained()->onDelete('cascade');
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tratamientos');
    }
};
