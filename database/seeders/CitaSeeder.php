<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('citas')->insert([
            [
                'medico_id' => 1,
                'paciente_id' => 1,
                'fecha_hora' => '2021-05-30 10:15:00',
            ],
            /*[
                'medico_id' => 1,
                'paciente_id' => 2,
                'fecha_hora' => '2021-06-30 09:30:00',
            ],
            [
                'medico_id' => 2,
                'paciente_id' => 2,
                'fecha_hora' => '2021-07-20 11:30:00',
            ],*/
        ]);


        /*DB::table('cita_medicamento')->insert([
            [
                'cita_id' => 1,
                'medicamento_id' => 1,
                'inicio' => '2021-05-31',
                'fin' => '2021-06-07',
                'tomas_dia' => 3,
                'comentarios' => 'Tomar después de las comidas',
            ],
            [
                'cita_id' => 2,
                'medicamento_id' => 2,
                'inicio' => '2021-06-30',
                'fin' => '2021-07-15',
                'tomas_dia' => 2,
                'comentarios' => 'El paciente presenta reacciones alérgicas',
            ],
            [
                'cita_id' => 2,
                'medicamento_id' => 1,
                'inicio' => '2021-06-30',
                'fin' => '2021-07-10',
                'tomas_dia' => 1,
                'comentarios' => 'Se especifica la toma',
            ],
        ]);*/
    }
}
