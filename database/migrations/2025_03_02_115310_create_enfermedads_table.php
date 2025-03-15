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
        Schema::create('enfermedads', function (Blueprint $table) {
            $table->id();
           // $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            //Información del trasplante
            $table->integer('dias_desde_trasplante')->nullable();
            $table->string('tipo_trasplante');
            //tipo_trasplante:['alogénico emparentado','alogénico no emparentado','autólogo ','singénico']
            $table->date('fecha_trasplante');
            $table->string('origen_trasplante');
            //origen_trasplante:['médula ósea','sangre periférica'])
            $table->string('identidad_hla');
            //identidad_hla:['idéntico','disparidad clase I','disparidad clase II']
            $table->string('tipo_acondicionamiento'); //de intensidad reducida o mieloablativo
            $table->string('seropositividad_donante');
            $table->string('seropositividad_receptor');
            //histórico de diagnósticos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enfermedads');
    }
};
