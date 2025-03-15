<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cita_medicamento', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('tomas_dia');
            $table->text('comentarios')->nullable();
            $table->date('inicio');
            $table->date('fin');
            $table->foreignId('cita_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicamento_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cita_medicamento');
    }
};
