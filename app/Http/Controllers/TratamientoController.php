<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;
use App\Http\Requests\Tratamiento\UpdateTratamientoRequest;
use App\Http\Requests\Tratamiento\StoreTratamientoRequest;
use App\Http\Requests\Tratamiento\UpdateTratamientoRequest as TratamientoUpdateTratamientoRequest;
use App\Models\Tratamiento;
use App\Models\Medicamento;
use App\Models\Paciente;


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
        $pacientes = Paciente::all();
        $medicos = Medico::all();

        return view('tratamientos/create',['pacientes'=>$pacientes,['medicos'=>$medicos]]);
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
            session()->flash('warning', 'No pudo borrarse el registro.');
        return redirect()->route('tratamientos.index');
    }

    //attach_medicamento
    public function attach_medicamento(Request $request, Tratamiento $tratamiento)
    {
        
        $this->validateWithBag('attach', $request, [
            'medicamento_id' => 'required|exists:medicos,id',
            'fecha_ini_linea' => 'required|date',
            'fecha_fin_linea' => 'required|date|after:fecha_ini_linea',
            'fecha_resp_linea' => 'required|date|after:fecha_ini_linea',
            'observaciones' => 'nullable|string',
            'tomas' => 'required|numeric|min:0',
            'duracion_linea' => 'required|numeric|min:0',
            'duracion_total' => 'required|numeric|min:0',

        ]);
        
        $tratamiento->medicamentos()->attach($request->medicamento_id, [
            'fecha_ini_linea' => $request->fecha_ini_linea,
            'fecha_fin_linea' => $request->fecha_fin_linea,
            'fecha_resp_linea' => $request->fecha_resp_linea,
            'observaciones' => $request->observaciones,
            'tomas' => $request->tomas_dia,
            'duracion_linea' => $request->duracion_linea,
            'duracion_total' => $request->duracion_total

        ]);
        return redirect()->route('tratamientos.edit', $tratamiento->id);
    }

    public function detach_medicamento(tratamiento $tratamiento, Medicamento $medicamento)
    {
        $tratamiento->medicamentos()->detach($medicamento->id);
        return redirect()->route('tratamientos.edit', $tratamiento->id);
    }
    
}
