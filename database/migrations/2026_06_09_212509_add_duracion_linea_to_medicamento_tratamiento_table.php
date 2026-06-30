<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('medicamento_tratamiento', function (Blueprint $table) {
            $table->integer('duracion_linea')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('medicamento_tratamiento', function (Blueprint $table) {
            $table->dropColumn('duracion_linea');
        });
    }
};