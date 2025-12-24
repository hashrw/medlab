<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReglaDecisionRequest;
use App\Http\Requests\UpdateReglaDecisionRequest;
use App\Models\ReglaDecision;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReglaController extends Controller
{
    public function index(): View
    {
        $reglas = ReglaDecision::query()
            ->orderBy('prioridad')   // si existe; si no, cambia a ->orderBy('id')
            ->orderBy('id')
            ->paginate(15);

        return view('reglas.index', compact('reglas'));
    }

    public function create(): View
    {
        return view('reglas.create');
    }

    public function store(StoreReglaDecisionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Si prioridad no viene, ponemos un default coherente
        if (!array_key_exists('prioridad', $data) || $data['prioridad'] === null) {
            $data['prioridad'] = 100;
        }

        $regla = ReglaDecision::create($data);

        return redirect()
            ->route('reglas.show', $regla)
            ->with('status', 'Regla creada correctamente.');
    }

    public function show(ReglaDecision $regla): View
    {
        return view('reglas.show', compact('regla'));
    }

    public function edit(ReglaDecision $regla): View
    {
        return view('reglas.edit', compact('regla'));
    }

    public function update(UpdateReglaDecisionRequest $request, ReglaDecision $regla): RedirectResponse
    {
        $data = $request->validated();

        $regla->update($data);

        return redirect()
            ->route('reglas.show', $regla)
            ->with('status', 'Regla actualizada correctamente.');
    }

    public function destroy(ReglaDecision $regla): RedirectResponse
    {
        $regla->delete();

        return redirect()
            ->route('reglas.index')
            ->with('status', 'Regla eliminada correctamente.');
    }
}
