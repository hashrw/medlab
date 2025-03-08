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
        DB::table('sintomas')->insert([
            [
                'sintoma' => "adfg",
                'manif_clinica' => "sfdxg",
                'organo_id' => 3

            ],
        ]);
    }
}
