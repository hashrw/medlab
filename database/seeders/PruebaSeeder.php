<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PruebaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pruebas')->insert([
            [
                'nombre' => 'Albúmina sérica',
                'comentario' => 'Valor bajo post-trasplante',
                'resultado' => 'Albúmina 2.9 g/dL',
                'fecha' => Carbon::now()->subDays(15),
                'tipo_prueba_id' => 1, // Analítica
            ],
            [
                'nombre' => 'Biopsia de piel',
                'comentario' => 'Estudio de lesión dérmica',
                'resultado' => 'Degeneración vacuolar e infiltrado linfocitario',
                'fecha' => Carbon::now()->subDays(28),
                'tipo_prueba_id' => 2, // Histológica
            ],
            [
                'nombre' => 'Electromiografía',
                'comentario' => 'Paciente con debilidad muscular',
                'resultado' => 'Actividad espontánea y patrón miopático',
                'fecha' => Carbon::now()->subDays(20),
                'tipo_prueba_id' => 3, // Funcional / Neurofisiológica
            ],
            [
                'nombre' => 'Cultivo bronquial',
                'comentario' => 'Sospecha de infección pulmonar',
                'resultado' => 'Aspergillus fumigatus aislado',
                'fecha' => Carbon::now()->subDays(10),
                'tipo_prueba_id' => 4, // Microbiológica / Infecciosa
            ],
            [
                'nombre' => 'Registro del trasplante',
                'comentario' => 'Fecha del trasplante alogénico',
                'resultado' => 'Trasplante de células madre hematopoyéticas',
                'fecha' => Carbon::now()->subMonths(8),
                'tipo_prueba_id' => 5, // Prueba de trasplante
            ],
        ]);
    }
}
