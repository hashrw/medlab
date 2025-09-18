<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tratamiento;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Medicamento;
use Illuminate\Support\Carbon;
use RuntimeException;

class TratamientoSeeder extends Seeder
{
    public function run(): void
    {
        // --- Precondiciones mínimas ---
        // Paciente y Médico deben existir (los crea tu User/Medico/Paciente seeder).
        $paciente = Paciente::inRandomOrder()->first();
        $medico   = Medico::inRandomOrder()->first();

        if (!$paciente || !$medico) {
            throw new RuntimeException('Seeders de Paciente y Medico deben ejecutarse antes de TratamientoSeeder.');
        }

        // Asegura al menos 2 medicamentos sin depender de factories
        if (Medicamento::count() < 2) {
            Medicamento::firstOrCreate(
                ['nombre' => 'Prednisona', 'miligramos' => 50],
                []
            );
            Medicamento::firstOrCreate(
                ['nombre' => 'Tacrolimus', 'miligramos' => 1],
                []
            );
        }

        // --- Crea un tratamiento base ---
        $t = Tratamiento::create([
            'tratamiento'      => 'Corticoides pauta inicial',
            'fecha_asignacion' => now()->subDays(10),
            'descripcion'      => 'Pauta base para fase aguda',
            'paciente_id'      => $paciente->id,
            'medico_id'        => $medico->id,
        ]);

        // Selecciona 2 medicamentos distintos
        $m1 = Medicamento::inRandomOrder()->first();
        $m2 = Medicamento::whereKeyNot($m1->id)->inRandomOrder()->first() ?? $m1;

        // Línea 1 (pivote)
        $t->lineasTratamiento()->attach($m1->id, [
            'fecha_ini_linea'  => now()->subDays(9)->toDateString(),
            'fecha_fin_linea'  => now()->subDays(2)->toDateString(),
            'fecha_resp_linea' => now()->subDays(5)->toDateString(),
            'observaciones'    => 'Buena tolerancia',
            'tomas'            => 2,
            'duracion_linea'   => 7,
            // ❌ NUNCA: 'duracion_total'
        ]);

        // Línea 2 (pivote)
        $t->lineasTratamiento()->attach($m2->id, [
            'fecha_ini_linea'  => now()->subDays(2)->toDateString(),
            'fecha_fin_linea'  => now()->toDateString(),
            'fecha_resp_linea' => now()->subDay()->toDateString(),
            'observaciones'    => 'Ajuste de dosis',
            'tomas'            => 1,
            'duracion_linea'   => 2,
        ]);

        // (Opcional) crea más tratamientos si quieres poblar: duplica el bloque anterior cambiando fechas/nombres.
    }
}
