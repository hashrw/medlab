<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->tipo_usuario_id == 1) { // Médico
            return view('dashboard.medico');
        } elseif ($user->tipo_usuario_id == 2) { // Paciente
            return view('dashboard.paciente');
        }

        // Si no coincide con ningún tipo, redirigir a la vista por defecto
        return view('dashboard');
    }
}
