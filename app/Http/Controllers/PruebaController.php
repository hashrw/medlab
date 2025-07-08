<?php

namespace App\Http\Controllers;
use App\Http\Requests\Prueba\StorePruebaRequest;
use App\Http\Requests\Prueba\UpdatePruebaRequest;
use App\Models\Prueba;
use Illuminate\Http\Request;

class PruebaController extends Controller
{
    /**
     * Display a listing of the resource.
     */public function index()
    {
        $this->authorize('viewAny', Prueba::class);
        $pruebas = Prueba::paginate(25);
        return view('/pruebas/index', ['pruebas' => $pruebas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Prueba::class);
        return view('pruebas/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePruebaRequest $request)
    {
        $prueba = new Prueba($request->validated());
        $prueba->save();
        session()->flash('success', 'registro creado correctamente.');
        return redirect()->route('pruebas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Prueba $prueba)
    {
        $this->authorize('view', $prueba);
        return view('pruebas/show', ['prueba' => $prueba]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prueba $prueba)
    {
        $this->authorize('update', $prueba);
        return view('pruebas/edit', ['prueba' => $prueba]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePruebaRequest $request, Prueba $prueba)
    {
        $this->authorize('update', $prueba);
        $prueba->fill($request->validated());
        $prueba->save();
        session()->flash('success', 'Registro modificado correctamente.');
        return redirect()->route('pruebas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prueba $prueba)
    {
        $this->authorize('delete', $prueba);
        if($prueba->delete())
            session()->flash('success', 'Registro borrado correctamente.');
        else
            session()->flash('warning', 'No pudo borrarse el registro.');
        return redirect()->route('pruebas.index');
    }
}
