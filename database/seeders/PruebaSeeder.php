<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PruebaSeeder extends Seeder
{
    public function run(): void
    {
        $pacienteId = DB::table('pacientes')->orderBy('id')->value('id');

        if (!$pacienteId) {
            dump('PruebaSeeder: no hay pacientes. Seeder abortado.');
            return;
        }

        DB::table('pruebas')->insert([
            [
                'paciente_id' => $pacienteId,
                'nombre' => 'Albúmina sérica',
                'comentario' => 'Valor bajo post-trasplante',
                'resultado' => 'Albúmina 2.9 g/dL',
                'fecha' => Carbon::now()->subDays(15),
                'tipo_prueba_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'paciente_id' => $pacienteId,
                'nombre' => 'Biopsia de piel',
                'comentario' => 'Estudio de lesión dérmica',
                'resultado' => 'Degeneración vacuolar e infiltrado linfocitario',
                'fecha' => Carbon::now()->subDays(28),
                'tipo_prueba_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'paciente_id' => $pacienteId,
                'nombre' => 'Electromiografía',
                'comentario' => 'Paciente con debilidad muscular',
                'resultado' => 'Actividad espontánea y patrón miopático',
                'fecha' => Carbon::now()->subDays(20),
                'tipo_prueba_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'paciente_id' => $pacienteId,
                'nombre' => 'Cultivo bronquial',
                'comentario' => 'Sospecha de infección pulmonar',
                'resultado' => 'Aspergillus fumigatus aislado',
                'fecha' => Carbon::now()->subDays(10),
                'tipo_prueba_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'paciente_id' => $pacienteId,
                'nombre' => 'Registro del trasplante',
                'comentario' => 'Fecha del trasplante alogénico',
                'resultado' => 'Trasplante de células madre hematopoyéticas',
                'fecha' => Carbon::now()->subMonths(8),
                'tipo_prueba_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
