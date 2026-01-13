<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([

            // 1) Usuarios y maestros básicos
            UserSeeder::class,
            EspecialidadSeeder::class,
            EstadoSeeder::class,
            ComienzoCronicaSeeder::class,
            InfeccionSeeder::class,

            // 2) Estructura clínica
            MedicoSeeder::class,
            OrganoSeeder::class,

            // 3) Síntomas (con dependencia fuerte)
            SintomaSeeder::class,
            SintomaAliasSeeder::class,

            // 4) Medicación (CRÍTICO para tratamientos)
            MedicamentoSeeder::class,
            MedicamentoAliasSeeder::class,

            // 5) Pacientes y contexto clínico
            PacienteSeeder::class,
            TrasplanteSeeder::class,
            //OrganoPacienteSeeder::class,

            // 6) Diagnóstico
            OrigenDiagnosticoSeeder::class,
            ReglaDecisionSeeder::class,
            //DiagnosticoSeeder::class,

            // 7) Tratamiento (reglas + datos)
            ReglaTratamientoSeeder::class,
           // TratamientoSeeder::class,

            // 8) Pruebas clínicas
            TipoPruebaSeeder::class,
            PruebaSeeder::class,
            // CitaSeeder::class,
        ]);
    }
}
