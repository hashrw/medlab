<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Domain\Clinical\CasoClinicoFactory;
use App\Models\Paciente;
use Illuminate\Http\Request;

class CasoClinicoController extends Controller
{
    public function show(Request $request)
    {
        $data = $request->validate([
            'paciente_id' => ['required', 'integer', 'exists:pacientes,id'],
            // opcional: fecha de corte para reproducibilidad
            'as_of' => ['nullable', 'date'],
        ]);

        $paciente = Paciente::findOrFail($data['paciente_id']);

        $asOf = isset($data['as_of']) ? \Carbon\Carbon::parse($data['as_of']) : null;

        $casoClinico = CasoClinicoFactory::fromPaciente($paciente, $asOf);

        return response()->json($casoClinico->toArray());
    }
}