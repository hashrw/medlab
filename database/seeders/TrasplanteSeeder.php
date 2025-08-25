<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrasplanteSeeder extends Seeder
{
    public function run(): void
    {
        // Tipos de trasplante
        $tiposTrasplante = [
            'alogénico emparentado',
            'alogénico no emparentado',
            'autólogo',
            'singénico',
        ];

        // Orígenes de trasplante
        $origenesTrasplante = [
            'médula ósea',
            'sangre periférica',
        ];

        // Identidades HLA
        $identidadesHLA = [
            'idéntico',
            'disparidad clase I',
            'disparidad clase II',
        ];

        // Tipos de acondicionamiento
        $tiposAcondicionamiento = [
            'mieloablativo',
            'de intensidad reducida',
        ];

        // Seropositividades
        $seropositividades = ['Positivo', 'Negativo'];

        // Crear 20 registros de enfermedades
        for ($i = 0; $i < 20; $i++) {
            DB::table('trasplantes')->insert([
                'tipo_trasplante' => $tiposTrasplante[array_rand($tiposTrasplante)],
                'fecha_trasplante' => Carbon::now()->subDays(rand(30, 730))->format('Y-m-d'), // Fecha aleatoria en los últimos 2 años
                'origen_trasplante' => $origenesTrasplante[array_rand($origenesTrasplante)],
                'identidad_hla' => $identidadesHLA[array_rand($identidadesHLA)],
                'tipo_acondicionamiento' => $tiposAcondicionamiento[array_rand($tiposAcondicionamiento)],
                'seropositividad_donante' => $seropositividades[array_rand($seropositividades)],
                'seropositividad_receptor' => $seropositividades[array_rand($seropositividades)],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'paciente_id' => 1,
            ]);
        }
    }
}