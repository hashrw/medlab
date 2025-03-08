<?php

namespace App\Http\Controllers;

use App\Http\Requests\Especialidad\StoreEspecialidadRequest;
use App\Http\Requests\Especialidad\UpdateEspecialidadRequest;
use App\Models\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Especialidad::class);
        $especialidades = Especialidad::paginate(25);
        return view('/especialidades/index', ['especialidades' => $especialidades]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Especialidad::class);
        return view('especialidades/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEspecialidadRequest $request)
    {
        $especialidad = new Especialidad($request->validated());
        $especialidad->save();
        session()->flash('success', 'Especialidad creada correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('especialidads.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Especialidad $especialidad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Especialidad $especialidad)
    {
        $this->authorize('update', $especialidad);
        return view('especialidades/edit', ['especialidad' => $especialidad]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEspecialidadRequest $request, Especialidad $especialidad)
    {
        $especialidad->fill($request->validated());
        $especialidad->save();
        session()->flash('success', 'Especialidad modificada correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('especialidads.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Especialidad $especialidad)
    {
        $this->authorize('delete', $especialidad);
        if($especialidad->delete())
            session()->flash('success', 'Especialidad borrada correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        else
            session()->flash('warning', 'No pudo borrarse la especialidad.');
        return redirect()->route('especialidads.index');
    }
}
