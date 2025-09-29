<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SintomaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sintomas')->insert([

           
            //  EICH Aguda
           
            [
                'sintoma' => 'Diarrea con sangre',
                'manif_clinica' => 'Heces con contenido hemático',
                'organo_id' => 1, // Tracto gastrointestinal
            ],
            [
                'sintoma' => 'Diarrea acuosa',
                'manif_clinica' => 'Heces líquidas abundantes y persistentes',
                'organo_id' => 1,
            ],
            [
                'sintoma' => 'Dolor abdominal',
                'manif_clinica' => 'Dolor y calambres en abdomen',
                'organo_id' => 1,
            ],
            [
                'sintoma' => 'Náuseas',
                'manif_clinica' => 'Sensación de malestar gástrico',
                'organo_id' => 1,
            ],
            [
                'sintoma' => 'Anorexia',
                'manif_clinica' => 'Pérdida de apetito',
                'organo_id' => 1,
            ],
            [
                'sintoma' => 'Hiperbilirrubinemia',
                'manif_clinica' => 'Aumento de bilirrubina sérica con ictericia',
                'organo_id' => 2, // Hígado
            ],
            [
                'sintoma' => 'Exantema maculopapular',
                'manif_clinica' => 'Lesiones cutáneas rojas, planas o elevadas',
                'organo_id' => 7, // Piel
            ],

           
            //  EICH Crónica (conceptos base)
           

            //  Ojos
            [
                'sintoma' => 'Ojo seco',
                'manif_clinica' => 'Sequedad ocular persistente',
                'organo_id' => 5,
            ],
            [
                'sintoma' => 'Fotofobia',
                'manif_clinica' => 'Sensibilidad a la luz',
                'organo_id' => 5,
            ],

            //  Tracto gastrointestinal
            [
                'sintoma' => 'Pérdida de peso',
                'manif_clinica' => 'Disminución significativa de peso corporal',
                'organo_id' => 1,
            ],
            [
                'sintoma' => 'Insuficiencia pancreática exocrina',
                'manif_clinica' => 'Déficit enzimático pancreático con mala digestión',
                'organo_id' => 1,
            ],
            [
                'sintoma' => 'Vómitos',
                'manif_clinica' => 'Expulsión del contenido gástrico',
                'organo_id' => 1,
            ],
            [
                'sintoma' => 'Síndrome de desgaste',
                'manif_clinica' => 'Pérdida de peso y masa muscular progresiva',
                'organo_id' => 1,
            ],

            //  Articulaciones y músculos
            [
                'sintoma' => 'Contracturas',
                'manif_clinica' => 'Pérdida de movilidad por rigidez articular',
                'organo_id' => 10,
            ],
            [
                'sintoma' => 'Artritis/artralgia',
                'manif_clinica' => 'Dolor o inflamación articular',
                'organo_id' => 10,
            ],
            [
                'sintoma' => 'Calambres musculares',
                'manif_clinica' => 'Espasmos musculares dolorosos',
                'organo_id' => 10,
            ],
            [
                'sintoma' => 'Edema',
                'manif_clinica' => 'Acumulación de líquido en extremidades',
                'organo_id' => 10,
            ],

            //  Piel
            [
                'sintoma' => 'Cambios escleróticos de la piel',
                'manif_clinica' => 'Lesiones cutáneas con endurecimiento dérmico',
                'organo_id' => 7,
            ],

            //  Hígado
            [
                'sintoma' => 'ALT elevada',
                'manif_clinica' => 'Transaminasas (ALT) elevadas',
                'organo_id' => 2,
            ],
            [
                'sintoma' => 'Fosfatasa alcalina elevada',
                'manif_clinica' => 'Niveles altos de fosfatasa alcalina',
                'organo_id' => 2,
            ],

            //  Boca
            [
                'sintoma' => 'Atrofia mucosa',
                'manif_clinica' => 'Disminución del grosor de la mucosa bucal',
                'organo_id' => 4,
            ],
            [
                'sintoma' => 'Dolor bucal',
                'manif_clinica' => 'Dolor localizado en cavidad oral',
                'organo_id' => 4,
            ],
            [
                'sintoma' => 'Gingivitis',
                'manif_clinica' => 'Inflamación de encías',
                'organo_id' => 4,
            ],
            [
                'sintoma' => 'Úlceras orales',
                'manif_clinica' => 'Lesiones ulceradas en mucosa oral',
                'organo_id' => 4,
            ],
            [
                'sintoma' => 'Xerostomía',
                'manif_clinica' => 'Sequedad oral persistente',
                'organo_id' => 4,
            ],

            //  Genitales
            [
                'sintoma' => 'Dispareunia',
                'manif_clinica' => 'Dolor en relaciones sexuales',
                'organo_id' => 12,
            ],
            [
                'sintoma' => 'Liquen esclerosus-like',
                'manif_clinica' => 'Lesiones genitales similares a liquen esclerosus',
                'organo_id' => 12,
            ],
            [
                'sintoma' => 'Úlceras genitales',
                'manif_clinica' => 'Lesiones ulceradas en mucosa genital',
                'organo_id' => 12,
            ],

            //  Pulmones
            [
                'sintoma' => 'Disnea',
                'manif_clinica' => 'Dificultad para respirar',
                'organo_id' => 8,
            ],
            [
                'sintoma' => 'Tos seca',
                'manif_clinica' => 'Tos persistente sin producción de flema',
                'organo_id' => 8,
            ],

            //  Uñas
            [
                'sintoma' => 'Onicolisis',
                'manif_clinica' => 'Separación de la uña del lecho ungueal',
                'organo_id' => 9,
            ],
            [
                'sintoma' => 'Pterigion ungueal',
                'manif_clinica' => 'Adherencia de la cutícula hacia la superficie de la uña',
                'organo_id' => 9,
            ],
        ]);
    }
}
