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
            InfeccionSeeder::class,
            ComienzoCronicaSeeder::class,
            EstadoSeeder::class,
            EspecialidadSeeder::class,
            MedicamentoSeeder::class,
            UserSeeder::class,
            MedicoSeeder::class,
            EnfermedadSeeder::class,
            TratamientoSeeder::class,
            OrganoSeeder::class,
            SintomaSeeder::class,
             PacienteSeeder::class,
            DiagnosticoSeeder::class,
            ReglaDecisionSeeder::class,
        ]);
    }
}
