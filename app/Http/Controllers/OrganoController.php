<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organo\StoreOrganoRequest;
use App\Http\Requests\Organo\UpdateOrganoRequest;
use Illuminate\Http\Request;
use App\Models\Organo;

class OrganoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Organo::class);
        $organos = Organo::paginate(25);
        return view('/organos/index', ['organos' => $organos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Organo::class);
        return view('organos/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganoRequest $request)
    {
        $organo = new Organo($request->validated());
        $organo->save();
        session()->flash('success', 'registro creado correctamente.');
        return redirect()->route('organos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Organo $organo)
    {
        $this->authorize('view', $organo);
        return view('organos/show', ['organo' => $organo]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organo $organo)
    {
        $this->authorize('update', $organo);
        return view('organos/edit', ['organo' => $organo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganoRequest $request, Organo $organo)
    {
        $this->authorize('update', $organo);
        $organo->fill($request->validated());
        $organo->save();
        session()->flash('success', 'Registro modificado correctamente.');
        return redirect()->route('organos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organo $organo)
    {
        $this->authorize('delete', $organo);
        if($organo->delete())
            session()->flash('success', 'Registro borrado correctamente.');
        else
            session()->flash('warning', 'No pudo borrarse el registro.');
        return redirect()->route('organos.index');
    }
}
