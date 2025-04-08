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
            InfeccionSeeder::class,ComienzoCronicaSeeder::class,EstadoEnfermedadSeeder::class,EspecialidadSeeder::class, MedicamentoSeeder::class, UserSeeder::class, MedicoSeeder::class, OrganoSeeder::class, SintomaSeeder::class,EnfermedadSeeder::class, TratamientoSeeder::class,
            PacienteSeeder::class,DiagnosticoSeeder::class
        ]);  
    }
}
