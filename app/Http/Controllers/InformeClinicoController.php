<?php

namespace App\Http\Controllers;

use App\Models\InformeClinico;
use Illuminate\Http\Request;

class InformeClinicoController extends Controller
{
    public function estado(InformeClinico $informeClinico)
    {
        $informeClinico->load('diagnostico');

        return response()->json([
            'status' => $informeClinico->status,
            'html' => view('diagnosticos.partials.clinical-report-panel', [
                'diagnostico' => $informeClinico->diagnostico,
                'ultimoInformeClinico' => $informeClinico,
            ])->render(),
        ]);
    }
}