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
        Schema::create('trasplantes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_trasplante')->nullable();
            $table->date('fecha_trasplante')->nullable();
            $table->string('origen_trasplante')->nullable();
            $table->string('identidad_hla')->nullable();
            $table->string('tipo_acondicionamiento')->nullable(); //de intensidad reducida o mieloablativo
            $table->string('seropositividad_donante')->nullable();
            $table->string('seropositividad_receptor')->nullable();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trasplantes');
    }
};
