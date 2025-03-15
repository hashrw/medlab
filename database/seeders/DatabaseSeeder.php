<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            EspecialidadSeeder::class, MedicamentoSeeder::class, UserSeeder::class, MedicoSeeder::class, OrganoSeeder::class, SintomaSeeder::class,EnfermedadSeeder::class, DiagnosticoSeeder::class, TratamientoSeeder::class,
            PacienteSeeder::class
        ]);  
    }
}
