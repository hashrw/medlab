<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Paciente;

class TrasplanteSeeder extends Seeder
{
    public function run(): void
    {
        $tiposTrasplante = [
            'alogénico emparentado',
            'alogénico no emparentado',
            'autólogo',
            'singénico',
        ];

        $origenesTrasplante = [
            'médula ósea',
            'sangre periférica',
        ];

        $identidadesHLA = [
            'idéntico',
            'disparidad clase I',
            'disparidad clase II',
        ];

        $tiposAcondicionamiento = [
            'mieloablativo',
            'de intensidad reducida',
        ];

        $seropositividades = ['Positivo', 'Negativo'];

        // Obtener todos los pacientes existentes
        $pacientes = Paciente::all();

        if ($pacientes->isEmpty()) {
            $this->command->warn('⚠️ No hay pacientes en la base de datos. Ejecuta primero el PacienteSeeder.');
            return;
        }

        // Crear trasplantes distribuidos entre los pacientes
        foreach ($pacientes as $paciente) {
            // cada paciente puede tener entre 1 y 3 trasplantes
            $numTrasplantes = rand(1, 3);

            for ($i = 0; $i < $numTrasplantes; $i++) {
                DB::table('trasplantes')->insert([
                    'tipo_trasplante' => $tiposTrasplante[array_rand($tiposTrasplante)],
                    'fecha_trasplante' => Carbon::now()->subDays(rand(30, 730))->format('Y-m-d'),
                    'origen_trasplante' => $origenesTrasplante[array_rand($origenesTrasplante)],
                    'identidad_hla' => $identidadesHLA[array_rand($identidadesHLA)],
                    'tipo_acondicionamiento' => $tiposAcondicionamiento[array_rand($tiposAcondicionamiento)],
                    'seropositividad_donante' => $seropositividades[array_rand($seropositividades)],
                    'seropositividad_receptor' => $seropositividades[array_rand($seropositividades)],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'paciente_id' => $paciente->id,
                ]);
            }
        }
    }
}
