<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organo_paciente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->foreignId('organo_id')->constrained()->onDelete('cascade');
            $table->string('score_nih');
            $table->date('fecha_evaluacion');
            $table->json('sintomas_asociados')->nullable(); //FK con sintomas o ¿?
            $table->text('comentario')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organo_paciente');
    }
};
