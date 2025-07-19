<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trasplante;
use App\Models\Paciente;
use App\Http\Requests\Trasplante\StoretrasplanteRequest;
use App\Http\Requests\Trasplante\UpdatetrasplanteRequest;

class trasplanteController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', trasplante::class);
        $trasplantes = trasplante::paginate(25);
        return view('/trasplantes/index', ['trasplantes' => $trasplantes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', trasplante::class);
        $pacientes = Paciente::all();
        return view('trasplantes/create', ['pacientes' => $pacientes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoretrasplanteRequest $request)
    {
        $trasplante = new trasplante($request->validated());
        $trasplante->save();
        session()->flash('success', 'Registro creado correctamente.');
        return redirect()->route('trasplantes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(trasplante $trasplante)
    {
        $this->authorize('view', $trasplante);
        return view('trasplantes/show', ['trasplante' => $trasplante]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(trasplante $trasplante)
    {
        $this->authorize('update', $trasplante);
        return view('trasplantes/edit', ['trasplante' => $trasplante]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatetrasplanteRequest $request, trasplante $trasplante)
    {
        $this->authorize('update', $trasplante);
        $trasplante->fill($request->validated());
        $trasplante->save();
        session()->flash('success', 'Registro modificado correctamente.');
        return redirect()->route('trasplantes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(trasplante $trasplante)
    {
        $this->authorize('delete', $trasplante);
        if($trasplante->delete())
            session()->flash('success', 'Registro borrado correctamente.');
        else
            session()->flash('warning', 'No pudo borrarse el registro.');
        return redirect()->route('trasplantes.index');
    }
}
