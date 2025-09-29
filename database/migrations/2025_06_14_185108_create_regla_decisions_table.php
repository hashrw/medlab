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
        Schema::create('regla_decisions', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->json('condiciones')->nullable();
            $table->json('diagnostico')->nullable();
            $table->string('tipo_recomendacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regla_decisions');
    }
};
