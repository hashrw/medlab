<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            // Nota: change() puede requerir doctrine/dbal
            $table->unsignedBigInteger('medico_id')->nullable()->change();

            $table->string('estado', 20)->default('pendiente')->after('paciente_id');

            $table->string('motivo', 120)->nullable()->after('estado');
            $table->text('motivo_detalle')->nullable()->after('motivo');

            $table->dateTime('preferencia_fecha_hora')->nullable()->after('motivo_detalle');

            $table->text('comentario_medico')->nullable()->after('preferencia_fecha_hora');
            $table->timestamp('respondida_at')->nullable()->after('comentario_medico');
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn([
                'estado',
                'motivo',
                'motivo_detalle',
                'preferencia_fecha_hora',
                'comentario_medico',
                'respondida_at',
            ]);

            // Revertir nullable si el esquema original lo tenía NOT NULL
            // (Si originalmente ya era nullable, elimina esta línea.)
            $table->unsignedBigInteger('medico_id')->nullable(false)->change();
        });
    }
};
