<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pacientes')->insert([
            [
                'nuhsa' => "AN1234567891",
                'fecha_nacimiento' => '1961-05-30',
                'peso' => 74.6,
                'altura' => 164,
                'sexo' => "M",
                'user_id' => 2,
            ]
        ]);
    }
}
