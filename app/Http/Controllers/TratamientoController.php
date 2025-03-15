<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Paciente\UpdateTratamientoRequest;
use App\Http\Requests\Paciente\StoreTratamientoRequest;
use App\Models\Tratamiento;


class TratamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Tratamiento::class);
        $tratamientos = Tratamiento::paginate(25);
        return view('/tratamientos/index', ['tratamientos' => $tratamientos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Tratamiento::class);
        return view('tratamientos/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTratamientoRequest $request)
    {
        $tratamiento = new Tratamiento($request->validated());
        $tratamiento->save();
        session()->flash('success', 'tratamiento creado correctamente.');
        return redirect()->route('tratamientos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tratamiento $tratamiento)
    {
        $this->authorize('view', $tratamiento);
        return view('tratamientos/show', ['tratamiento' => $tratamiento]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tratamiento $tratamiento)
    {
        $this->authorize('update', $tratamiento);
        return view('tratamientos/edit', ['tratamiento' => $tratamiento]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTratamientoRequest $request, Tratamiento $tratamiento)
    {
        $this->authorize('update', $tratamiento);
        $tratamiento->fill($request->validated());
        $tratamiento->save();
        session()->flash('success', 'Registro modificado correctamente.');
        return redirect()->route('tratamientos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tratamiento $tratamiento)
    {
        $this->authorize('delete', $tratamiento);
        if($tratamiento->delete())
            session()->flash('success', 'Registro borrado correctamente.');
        else
            session()->flash('warning', 'No pudo borrarse el Registro.');
        return redirect()->route('tratamientos.index');
    }
}
