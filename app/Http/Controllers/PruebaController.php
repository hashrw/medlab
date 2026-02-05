<?php

namespace App\Http\Controllers;

use App\Http\Requests\Prueba\StorePruebaRequest;
use App\Http\Requests\Prueba\UpdatePruebaRequest;
use App\Models\Prueba;
use App\Models\TipoPrueba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PruebaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Prueba::class);

        $user = Auth::user();

        $query = Prueba::query()->with(['tipo_prueba', 'paciente.usuarioAcceso']);

        // P0: scoping por rol
        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            abort_unless($medicoId, 403);

            $query->whereHas('paciente', function ($q) use ($medicoId) {
                $q->where('medico_id', $medicoId);
            });
        } elseif ($user->es_paciente) {
            $pacienteId = $user->paciente?->id;
            abort_unless($pacienteId, 403);

            $query->where('paciente_id', $pacienteId);
        }

        // FILTRO POR TIPO
        if ($request->filled('tipo')) {
            $query->where('tipo_prueba_id', $request->tipo);
        }

        //$pruebas = $query->orderBy('fecha', 'desc')->paginate(10)->withQueryString();
        $pruebas = $query->orderBy('fecha', 'desc')->paginate(10)->appends($request->query());

        $tipos = TipoPrueba::all();

        return view('pruebas.index', compact('pruebas', 'tipos'));
    }

    public function create()
    {
        $this->authorize('create', Prueba::class);
        return view('pruebas/create');
    }

    public function store(StorePruebaRequest $request)
    {
        $this->authorize('create', Prueba::class);

        $data = $request->validated();

        // P0: si el creador es médico, validar que el paciente es suyo
        if ($request->user()->es_medico && !empty($data['paciente_id'])) {
            $paciente = \App\Models\Paciente::find((int) $data['paciente_id']);

            if (!$paciente) {
                abort(404);
            }

            $this->authorize('view', $paciente);
        }

        // Si el creador es paciente, forzamos su propio paciente_id (evita spoof)
        if ($request->user()->es_paciente && $request->user()->paciente?->id) {
            $data['paciente_id'] = (int) $request->user()->paciente->id;
        }

        $prueba = new Prueba($data);
        $prueba->save();

        session()->flash('success', 'Registro creado correctamente.');
        return redirect()->route('pruebas.index');
    }


    public function show(Prueba $prueba)
    {
        $this->authorize('view', $prueba);

        $prueba->loadMissing(['tipo_prueba', 'paciente.usuarioAcceso']);

        return view('pruebas/show', ['prueba' => $prueba]);
    }

    public function edit(Prueba $prueba)
    {
        $this->authorize('update', $prueba);

        $prueba->loadMissing(['tipo_prueba', 'paciente.usuarioAcceso']);

        return view('pruebas/edit', ['prueba' => $prueba]);
    }

    public function update(UpdatePruebaRequest $request, Prueba $prueba)
    {
        $this->authorize('update', $prueba);

        $data = $request->validated();

        // P0: evitar que se reasigne prueba a otro paciente por update
        unset($data['paciente_id']);

        $prueba->fill($data);
        $prueba->save();

        session()->flash('success', 'Registro modificado correctamente.');
        return redirect()->route('pruebas.index');
    }

    public function destroy(Prueba $prueba)
    {
        $this->authorize('delete', $prueba);

        if ($prueba->delete()) {
            session()->flash('success', 'Registro borrado correctamente.');
        } else {
            session()->flash('warning', 'No pudo borrarse el registro.');
        }

        return redirect()->route('pruebas.index');
    }
}
