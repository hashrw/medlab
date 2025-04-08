<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TratamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  public function run()
        /* $table->string('tratamiento');
        $table->date('fecha_asignacion');
        $table->text('descripcion')->nullable();
        //se calcula a partir de la fecha de inicio de la 1ª línea de tratamiento y la fecha de fin de la última línea de tratamiento asignada.
        $table->integer('duracion_trat');
        $table->foreignId('medico_id')->constrained()->onDelete('cascade');
        $table->foreignId('paciente_id')->constrained()->onDelete('cascade') */
        DB::table('tratamientos')->insert([
            [
                'tratamiento' => "Oftalmología",
                'fecha_asignacion' => '2021-05-30',
                'descripcion' => "No aplica",
                'duracion_trat' => 1,
                'medico_id' => 1,
                ],
        ]);
    }
}
