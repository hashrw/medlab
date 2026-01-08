<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regla_tratamientos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->unsignedInteger('prioridad')->default(100);
            $table->boolean('activo')->default(true);

            /**
             * Condiciones clínicas de aplicación de la regla
             * Ej:
             * {
             *   "grado_eich": "moderada",
             *   "organos": {
             *     "Tracto gastrointestinal": { "score_min": 1 }
             *   }
             * }
             */
            $table->json('condiciones');

            /**
             * Acciones terapéuticas a ejecutar si la regla aplica
             * Ej:
             * {
             *   "tratamiento": {
             *     "tratamiento": "Plan EICH moderada",
             *     "descripcion": "Primera línea sistémica"
             *   },
             *   "lineas": [
             *     {
             *       "medicamento_alias": "prednisona",
             *       "duracion_linea": 14,
             *       "tomas": "según pauta"
             *     }
             *   ]
             * }
             */
            $table->json('acciones');

            // Trazabilidad / documentación clínica
            $table->string('fuente')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regla_tratamientos');
    }
};
