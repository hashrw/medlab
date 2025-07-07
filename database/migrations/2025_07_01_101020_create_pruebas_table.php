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
            $table->foreignId('id')->constrained()->onDelete('cascade');
            $table->string('nombre'); // Ej: espirometría, electromiografía, hipoalbuminemia,f_medulograma,f_espirometria,f_esplenectomia,f_eval_injerto etc... =>
            $table->string('tipo_prueba');
            $table->date('fecha')->nullable();
            $table->text('resultado')->nullable();
            $table->text('comentario')->nullable();
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
