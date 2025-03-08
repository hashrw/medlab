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
        Schema::create('medicamento_tratamiento', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_ini_linea')->nullable();
            //se calcula a partir de las fechas de inicio y fin de linea tratamiento actual.
            $table->integer('duracion_linea')->nullable();
            //la fecha de inicio de la primera línea del tratamiento y la fecha de fin de la última línea de tratamiento.
            $table->integer('duracion_total')->nullable();
            $table->date('fecha_fin_linea')->nullable();
            $table->date('fecha_resp_linea')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('tratamiento_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('medicamento_id')->constrained()->onDelete('cascade')->nullable();
            $table->integer('tomas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamento_tratamiento');
    }
};
