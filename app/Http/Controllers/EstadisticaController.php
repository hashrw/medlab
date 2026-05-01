<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstadisticaController extends Controller
{
    public function index()
    {
        return view('estadisticas.index', [
            'totalDiagnosticos' => \App\Models\Diagnostico::count(),
            'diagnosticosAgudos' => \App\Models\Diagnostico::where('tipo_enfermedad', 'aguda')->count(),
            'diagnosticosCronicos' => \App\Models\Diagnostico::where('tipo_enfermedad', 'cronica')->count(),
            'informesTotales' => \App\Models\InformeClinico::count(),
            'informesFallback' => \App\Models\InformeClinico::where('status', 'fallback')->count(),
            'tratamientosTotales' => \App\Models\Tratamiento::count(),
        ]);
    }
}
