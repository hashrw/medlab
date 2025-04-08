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
        Schema::create('diagnostico_sintoma', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_diagnostico')->nullable();
            $table->double('score_nih')->nullable();
            $table->foreignId('diagnostico_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('sintoma_id')->constrained()->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostico_sintoma');
    }
};
