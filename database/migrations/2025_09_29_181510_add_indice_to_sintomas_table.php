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
        Schema::table('sintomas', function (Blueprint $table) {
            // 🔹 Asegurar que un síntoma es único por órgano
            $table->unique(['organo_id', 'sintoma'], 'ux_sintomas_organo_sintoma');
        });
    }

    public function down(): void
    {
        Schema::table('sintomas', function (Blueprint $table) {
            $table->dropUnique('ux_sintomas_organo_sintoma');
        });
    }
};
