<?php

namespace App\Http\Controllers;

use App\Http\Requests\Medicamento\StoreMedicamentoRequest;
use App\Http\Requests\Medicamento\UpdateMedicamentoRequest;
use App\Models\Medicamento;

class MedicamentoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Medicamento::class);

        $medicamentos = Medicamento::paginate(25)->withQueryString();

        return view('/medicamentos/index', ['medicamentos' => $medicamentos]);
    }

    public function create()
    {
        $this->authorize('create', Medicamento::class);
        return view('medicamentos/create');
    }

    public function store(StoreMedicamentoRequest $request)
    {
        $medicamento = new Medicamento($request->validated());
        $medicamento->save();

        session()->flash('success', 'Registro creado correctamente.');
        return redirect()->route('medicamentos.index');
    }

    public function show(Medicamento $medicamento)
    {
        $this->authorize('view', $medicamento);
        return view('medicamentos/show', ['medicamento' => $medicamento]);
    }

    public function edit(Medicamento $medicamento)
    {
        $this->authorize('update', $medicamento);
        return view('medicamentos/edit', ['medicamento' => $medicamento]);
    }

    public function update(UpdateMedicamentoRequest $request, Medicamento $medicamento)
    {
        $this->authorize('update', $medicamento);

        $medicamento->fill($request->validated());
        $medicamento->save();

        session()->flash('success', 'Medicamento modificado correctamente.');
        return redirect()->route('medicamentos.index');
    }

    public function destroy(Medicamento $medicamento)
    {
        $this->authorize('delete', $medicamento);

        if ($medicamento->delete()) {
            session()->flash('success', 'Medicamento borrado correctamente.');
        } else {
            session()->flash('warning', 'No pudo borrarse el Medicamento.');
        }

        return redirect()->route('medicamentos.index');
    }
}
