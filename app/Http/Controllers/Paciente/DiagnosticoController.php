<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Diagnostico;
use App\Models\Paciente;
use Illuminate\Http\Request;

class DiagnosticoController extends Controller
{
    public function show(Request $request, Diagnostico $diagnostico)
    {
        $user = $request->user();
        $pacienteId = (int) ($user->paciente_id ?? 0);

        abort_unless($pacienteId > 0, 403);
        abort_unless((int) $diagnostico->paciente_id === $pacienteId, 403);

        // Cargar relaciones usadas por la vista (read-only)
        $diagnostico->load([
            'estado',
            'infeccion',
            'comienzo',
            'regla',
            'sintomas',
        ]);

        $paciente = Paciente::query()
            ->select('id', 'nuhsa')
            ->where('id', $pacienteId)
            ->first();

        return view('paciente.diagnosticos.show', compact('diagnostico', 'paciente'));
    }
}
