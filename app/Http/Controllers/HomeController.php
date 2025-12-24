<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Diagnostico;
use App\Models\Tratamiento;
use App\Models\Prueba;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return match (true) {
            (int) $user->tipo_usuario_id === 1 => redirect()->route('dashboard.medico'),
            (int) $user->tipo_usuario_id === 2 => redirect()->route('dashboard.paciente'),
            default => abort(403),
        };
    }

    public function medico(Request $request)
    {
        $stats = [
            'pacientes' => Paciente::count(),
            'diagnosticos' => Diagnostico::count(),
            'tratamientos' => Tratamiento::count(),
            'pruebas' => Prueba::count(),
        ];

        $ultimos = [
            'pacientes' => Paciente::latest('id')->limit(5)->get(),
            'diagnosticos' => Diagnostico::latest('id')->limit(5)->get(),
            'tratamientos' => Tratamiento::latest('id')->limit(5)->get(),
            'pruebas' => Prueba::latest('id')->limit(5)->get(),
        ];

        return view('dashboard.medico', compact('stats', 'ultimos'));
    }

    public function paciente(Request $request)
    {
        return view('dashboard.paciente');
    }

}
