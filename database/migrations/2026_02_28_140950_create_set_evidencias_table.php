<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('set_evidencias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('diagnostico_id')
                ->constrained('diagnosticos')
                ->onDelete('cascade');

            // estado del job
            $table->string('status', 20)->default('queued'); // queued|processing|done|failed
            $table->text('error_message')->nullable();

            // resultado del RAG (citas, metadatos)
            $table->json('payload')->nullable();

            $table->timestamps();

            $table->unique('diagnostico_id'); // 1 evidence pack por diagnóstico
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidence_packs');
    }
};
