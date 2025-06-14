<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Services\InferenciaDiagnosticoService;

class DiagnosticoInferidoController extends Controller
{
    public function store(Paciente $paciente)
    {
        $resultado = app(InferenciaDiagnosticoService::class)->ejecutar($paciente);

        return redirect()->route('pacientes.diagnosticos', $paciente)
            ->with('success', 'Diagnóstico inferido correctamente.');
    }
}
