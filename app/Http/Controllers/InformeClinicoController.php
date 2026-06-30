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

            'started_at' => optional($informeClinico->started_at)->format('d/m/Y H:i:s'),
            'finished_at' => optional($informeClinico->finished_at)->format('d/m/Y H:i:s'),
            'error_message' => $informeClinico->error_message,
            'fallback_reason' => $informeClinico->fallback_reason,

            'html' => view('diagnosticos.partials.clinical-report-panel', [
                'diagnostico' => $informeClinico->diagnostico,
                'ultimoInformeClinico' => $informeClinico,
            ])->render(),
        ]);
    }

    public function cancelar(InformeClinico $informeClinico)
    {
        if (!in_array($informeClinico->status, ['pending', 'processing'], true)) {
            return back()->with('warning', 'El informe ya no está en generación.');
        }

        $informeClinico->update([
            'status' => 'cancelled',
            'error_message' => 'Generación cancelada por el usuario.',
            'finished_at' => now(),
        ]);

        return back()->with('success', 'Generación de informe cancelada correctamente.');
    }

    public function validar(InformeClinico $informeClinico)
    {
        if ($informeClinico->status !== 'fallback') {
            return back()->with('warning', 'Sólo se pueden validar informes generados parcialmente.');
        }

        $informeClinico->update([
            'status' => 'completed',
            'error_message' => null,
            'fallback_reason' => null,
            'finished_at' => $informeClinico->finished_at ?? now(),
        ]);

        return back()->with('success', 'Informe validado como completo correctamente.');
    }
}