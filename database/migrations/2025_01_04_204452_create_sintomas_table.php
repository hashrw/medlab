<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('sintomas', function (Blueprint $table) {
            $table->id();
            $table->string('sintoma')->nullable();
            $table->string('manif_clinica')->nullable();
            //organo solo añado en modelo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sintomas');
    }
};
