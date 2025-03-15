<?php

namespace App\Http\Controllers;

use App\Http\Requests\Medicamento\StoreMedicamentoRequest;
use App\Http\Requests\Medicamento\UpdateMedicamentoRequest;
use App\Models\Medicamento;
use Illuminate\Http\Request;

class MedicamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Medicamento::class);
        $medicamentos = Medicamento::paginate(25);
        return view('/medicamentos/index', ['medicamentos' => $medicamentos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Medicamento::class);
        return view('medicamentos/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicamentoRequest $request)
    {
        $medicamento = new Medicamento($request->validated());
        $medicamento->save();
        session()->flash('success', 'Medicamento creado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('medicamentos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Medicamento $medicamento)
    {
        $this->authorize('view', $medicamento);
        return view('medicamentos/show', ['medicamento' => $medicamento]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medicamento $medicamento)
    {
        $this->authorize('update', $medicamento);
        return view('medicamentos/edit', ['medicamento' => $medicamento]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicamentoRequest $request, Medicamento $medicamento)
    {
        $this->authorize('update', $medicamento);
        $medicamento->fill($request->validated());
        $medicamento->save();
        session()->flash('success', 'Medicamento modificado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('medicamentos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medicamento $medicamento)
    {
        $this->authorize('delete', $medicamento);
        if($medicamento->delete())
            session()->flash('success', 'Medicamento borrado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        else
            session()->flash('warning', 'No pudo borrarse el Medicamento.');
        return redirect()->route('medicamentos.index');
    }
}
