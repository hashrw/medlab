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
            OrganoSeeder::class,
            SintomaSeeder::class,
            SintomaAliasSeeder::class,
            PacienteSeeder::class,
            MedicamentoSeeder::class,
            TratamientoSeeder::class,
            //CitaSeeder::class,
            TrasplanteSeeder::class,
            ReglaDecisionSeeder::class,
            OrigenDiagnosticoSeeder::class,
            DiagnosticoSeeder::class,
            OrganoPacienteSeeder::class,
            TipoPruebaSeeder::class,
            PruebaSeeder::class,
        ]);
    }
}
