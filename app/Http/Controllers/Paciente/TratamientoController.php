<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Tratamiento;
use Illuminate\Http\Request;

class TratamientoController extends Controller
{
    public function show(Request $request, Tratamiento $tratamiento)
    {
        $user = $request->user();
        $pacienteId = (int) ($user->paciente_id ?? 0);

        abort_unless($pacienteId > 0, 403);
        abort_unless((int) $tratamiento->paciente_id === $pacienteId, 403);

        $tratamiento->load([
            'diagnostico',
            'lineasTratamiento',
        ]);

        return view('paciente.tratamientos.show', compact('tratamiento'));
    }
}
