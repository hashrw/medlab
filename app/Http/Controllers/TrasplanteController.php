<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trasplante;
use App\Models\Paciente;
use App\Http\Requests\Trasplante\StoreTrasplanteRequest;
use App\Http\Requests\Trasplante\UpdateTrasplanteRequest;

class trasplanteController extends Controller
{
    public function index(Request $request)
    {

        $trasplante = Trasplante::query();
        if ($request->filled('tipo')) {
            $trasplante->where('tipo_trasplante', $request->tipo);

        }

        if ($request->filled('hla')) {
            $trasplante->where('identidad_hla', $request->hla);
        }

        if ($request->filled('serologia')) {
            $trasplante->where(function ($q) use ($request) {
                $q->where('seropositividad_donante', $request->serologia)
                    ->orWhere('seropositividad_receptor', $request->serologia);
            });
        }

        if ($request->filled('year')) {
            $trasplante->whereYear('fecha_trasplante', $request->year);
        }
        $trasplantes = $trasplante->paginate(12)->appends($request->query());
        return view('/trasplantes/index', ['trasplantes' => $trasplantes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Trasplante::class);
        $pacientes = Paciente::all();
        return view('trasplantes/create', ['pacientes' => $pacientes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTrasplanteRequest $request)
    {
        //$trasplante = new trasplante(attributes: $request->validated());
        //$trasplante->save();
        $trasplante = Trasplante::create($request->validated());
        session()->flash('success', 'Registro creado correctamente.');
        return redirect()->route('trasplantes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trasplante $trasplante)
    {
        $this->authorize('view', $trasplante);
        return view('trasplantes/show', ['trasplante' => $trasplante]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trasplante $trasplante)
    {
        $pacientes = Paciente::orderBy('nuhsa')->get();

        return view('trasplantes.edit', [
            'trasplante' => $trasplante,
            'pacientes' => $pacientes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTrasplanteRequest $request, Trasplante $trasplante)
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
    public function destroy(Trasplante $trasplante)
    {
        $this->authorize('delete', $trasplante);
        if ($trasplante->delete())
            session()->flash('success', 'Registro borrado correctamente.');
        else
            session()->flash('warning', 'No pudo borrarse el registro.');
        return redirect()->route('trasplantes.index');
    }
}
