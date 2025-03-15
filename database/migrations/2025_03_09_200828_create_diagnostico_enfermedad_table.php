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
        Schema::create('diagnostico_enfermedad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enfermedad_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('diagnostico_id')->constrained()->onDelete('cascade')->nullable();
            $table->string('grado_eich')->nullable(); //grado específico a tipo enfermedad//(grado 1, grado 2, grado 3 y grado 4 y grados EICH crónica: 'Leve', 'Moderada', 'Grave']
            $table->string('escala_karnofsky')->nullable(); //estado fase crónica general // ['ECOG 1', 'ECOG 2', 'ECOG 3', 'ECOG 4']
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostico_enfermedad');
    }
};
