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
        Schema::create('informes_clinicos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('diagnostico_id')
                ->constrained('diagnosticos')
                ->cascadeOnDelete();

            $table->foreignId('paciente_id')
                ->constrained('pacientes')
                ->cascadeOnDelete();

            $table->string('status')->default('pending');

            $table->json('clinical_report')->nullable();
            $table->json('traceability')->nullable();

            $table->boolean('llm_used')->default(false);
            $table->string('llm_model')->nullable();
            $table->text('fallback_reason')->nullable();
            $table->timestamp('generated_at')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informes_clinicos');
    }
};
