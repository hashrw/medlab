<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SintomaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar datos en la tabla 'sintomas'
        DB::table('sintomas')->insert([
            // Síntomas de EICH cutánea
            [
                'sintoma' => 'Eritema cutáneo',
                'manif_clinica' => 'Enrojecimiento y erupciones en la piel',
                'organo_id' => 7, // Piel
               
            ],
            [
                'sintoma' => 'Prurito intenso',
                'manif_clinica' => 'Picazón severa en la piel',
                'organo_id' => 7, // Piel
               
            ],

            // Síntomas de EICH gastrointestinal
            [
                'sintoma' => 'Diarrea crónica',
                'manif_clinica' => 'Diarrea persistente y acuosa',
                'organo_id' => 1, // Tracto gastrointestinal
               
            ],
            [
                'sintoma' => 'Dolor abdominal',
                'manif_clinica' => 'Dolor y calambres en el abdomen',
                'organo_id' => 1, // Tracto gastrointestinal
               
            ],

            // Síntomas de EICH hepática
            [
                'sintoma' => 'Ictericia',
                'manif_clinica' => 'Coloración amarillenta de la piel y ojos',
                'organo_id' => 4, // Hígado
               
            ],
            [
                'sintoma' => 'Hepatomegalia',
                'manif_clinica' => 'Agrandamiento del hígado',
                'organo_id' => 4, // Hígado
               
            ],

            // Síntomas de EICH pulmonar
            [
                'sintoma' => 'Disnea',
                'manif_clinica' => 'Dificultad para respirar',
                'organo_id' => 8, // Pulmones
               
            ],
            [
                'sintoma' => 'Tos seca',
                'manif_clinica' => 'Tos persistente sin producción de flema',
                'organo_id' => 8, // Pulmones
               
            ],

            // Síntomas de EICH ocular
            [
                'sintoma' => 'Sequedad ocular',
                'manif_clinica' => 'Ojos secos e irritados',
                'organo_id' => 5, // Ojos
               
            ],
            [
                'sintoma' => 'Fotofobia',
                'manif_clinica' => 'Sensibilidad a la luz',
                'organo_id' => 5, // Ojos
               
            ],
        ]);
    }
}
