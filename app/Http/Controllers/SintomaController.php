<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sintoma\StoreSintomaRequest;
use App\Http\Requests\Sintoma\UpdateSintomaRequest;
use App\Models\Organo;
use Illuminate\Http\Request;
use App\Models\Sintoma;

class SintomaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Sintoma::class);
        $sintomas = Sintoma::paginate(25);
        return view('/sintomas/index', ['sintomas' => $sintomas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Sintoma::class);
        $organos = Organo::all();

        return view('sintomas/create', ['organos' => $organos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSintomaRequest $request)
    {
        $sintoma = new Sintoma($request->validated());
        $sintoma->save();
        session()->flash('success', 'sintoma creado correctamente.');
        return redirect()->route('sintomas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sintoma $sintoma)
    {
        $this->authorize('view', $sintoma);
        return view('sintomas/show', ['sintoma' => $sintoma]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sintoma $sintoma)
    {
        $this->authorize('update', $sintoma);
        $organos = Organo::all();
        return view('sintomas/edit', ['sintoma' => $sintoma, 'organos' => $organos]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSintomaRequest $request, Sintoma $sintoma)
    {
        $this->authorize('update', $sintoma);
        $sintoma->fill($request->validated());
        $sintoma->save();
        session()->flash('success', 'Registro modificado correctamente.');
        return redirect()->route('sintomas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sintoma $sintoma)
    {
        $this->authorize('delete', $sintoma);
        if($sintoma->delete())
            session()->flash('success', 'Registro borrado correctamente.');
        else
            session()->flash('warning', 'No pudo borrarse el sintoma.');
        return redirect()->route('sintomas.index');
    }
}
