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
           UserSeeder::class,
            EspecialidadSeeder::class,
            MedicoSeeder::class,
            EstadoSeeder::class,
            ComienzoCronicaSeeder::class,
            InfeccionSeeder::class,
            EnfermedadSeeder::class,
            TratamientoSeeder::class,
            MedicamentoSeeder::class,
          //CitaSeeder::class,
            OrganoSeeder::class,
            SintomaSeeder::class,
            PacienteSeeder::class,
            ReglaDecisionSeeder::class,
            DiagnosticoSeeder::class,
            OrganoPacienteSeeder::class,
        ]);
    }
}
