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
        Schema::create('pruebas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: espirometría, electromiografía, hipoalbuminemia,f_medulograma,f_espirometria,f_esplenectomia,f_eval_injerto etc... =>
            $table->date('fecha')->nullable();
            $table->text('resultado')->nullable();
            $table->text('comentario')->nullable();
            $table->foreignId('paciente_id')
                ->constrained('pacientes')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pruebas');
    }
};
