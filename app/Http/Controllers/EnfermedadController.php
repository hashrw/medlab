<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Enfermedad;
use App\Http\Requests\StoreEnfermedadRequest;
use App\Http\Requests\UpdateEnfermedadRequest;

class EnfermedadController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Enfermedad::class);
        $enfermedads = Enfermedad::paginate(25);
        return view('/enfermedads/index', ['enfermedads' => $enfermedads]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Enfermedad::class);
        return view('enfermedads/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnfermedadRequest $request)
    {
        $enfermedad = new Enfermedad($request->validated());
        $enfermedad->save();
        session()->flash('success', 'Registro creado correctamente.');
        return redirect()->route('enfermedads.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Enfermedad $enfermedad)
    {
        $this->authorize('view', $enfermedad);
        return view('enfermedads/show', ['enfermedad' => $enfermedad]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enfermedad $enfermedad)
    {
        $this->authorize('update', $enfermedad);
        return view('enfermedads/edit', ['enfermedad' => $enfermedad]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnfermedadRequest $request, Enfermedad $enfermedad)
    {
        $this->authorize('update', $enfermedad);
        $enfermedad->fill($request->validated());
        $enfermedad->save();
        session()->flash('success', 'Registro modificado correctamente.');
        return redirect()->route('enfermedads.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enfermedad $enfermedad)
    {
        $this->authorize('delete', $enfermedad);
        if($enfermedad->delete())
            session()->flash('success', 'Registro borrado correctamente.');
        else
            session()->flash('warning', 'No pudo borrarse el registro.');
        return redirect()->route('enfermedads.index');
    }
}
