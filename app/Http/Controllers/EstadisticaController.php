<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Paciente;
use App\Models\Diagnostico;
use App\Models\Tratamiento;
use App\Models\InformeClinico;

class EstadisticaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->medico) {
            abort(403);
        }

        $medicoId = $user->medico->id;

        $pacienteIds = Paciente::where('medico_id', $medicoId)->pluck('id');

        $stats = [
            'pacientes' => $pacienteIds->count(),

            'diagnosticos' => Diagnostico::whereIn('paciente_id', $pacienteIds)->count(),

            'diagnosticos_agudos' => Diagnostico::whereIn('paciente_id', $pacienteIds)
                ->where('tipo_enfermedad', 'aguda')
                ->count(),

            'diagnosticos_cronicos' => Diagnostico::whereIn('paciente_id', $pacienteIds)
                ->where('tipo_enfermedad', 'cronica')
                ->count(),

            'tratamientos' => Tratamiento::whereIn('paciente_id', $pacienteIds)->count(),

            'informes' => InformeClinico::whereIn('paciente_id', $pacienteIds)->count(),

            'informes_fallback' => InformeClinico::whereIn('paciente_id', $pacienteIds)
                ->where('status', 'fallback')
                ->count(),
        ];

        return view('estadisticas.index', compact('stats'));
    }
}