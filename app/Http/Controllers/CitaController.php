<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cita\StoreCitaRequest;
use App\Http\Requests\Cita\UpdateCitaRequest;
use App\Models\Cita;
use App\Models\Medicamento;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Cita::class);
        $citas = Cita::orderBy('fecha_hora', 'desc')->paginate(25);
        if(Auth::user()->es_medico)
            $citas = Auth::user()->medico->citas()->orderBy('fecha_hora', 'desc')->paginate(25);
        elseif(Auth::user()->es_paciente)
            $citas = Auth::user()->paciente->citas()->orderBy('fecha_hora', 'desc')->paginate(25);
        return view('/citas/index', ['citas' => $citas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Cita::class);
        $medicos = Medico::all();
        $pacientes = Paciente::all();
        if(Auth::user()->es_medico)
            return view('citas/create', ['medico' => Auth::user()->medico, 'pacientes' => $pacientes]);
        elseif(Auth::user()->es_paciente)
            return view('citas/create', ['paciente' => Auth::user()->paciente, 'medicos' => $medicos]);
        return view('citas/create', ['pacientes' => $pacientes, 'medicos' => $medicos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCitaRequest $request)
    {
        $cita = new Cita($request->validated());
        $cita->save();
        session()->flash('success', 'Cita creada correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('citas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cita $cita)
    {
        $this->authorize('view', $cita);
        return view('citas/show', ['cita' => $cita]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cita $cita)
    {
        $this->authorize('update', $cita);
        //Le paso a la vista los medicamentos porque permito añadir una prescripción desde esa misma vista
        $medicamentos = Medicamento::all();
        $medicos = Medico::all();
        $pacientes = Paciente::all();
        if(Auth::user()->es_medico){
            return view('citas/edit', ['cita' => $cita, 'medico' => Auth::user()->medico, 'pacientes' => $pacientes, 'medicamentos' => $medicamentos]);
        }
        elseif(Auth::user()->es_paciente) {
            return view('citas/edit', ['cita' => $cita, 'paciente' => Auth::user()->paciente, 'medicos' => $medicos, 'medicamentos' => $medicamentos]);
        }
        return view('citas/edit', ['cita' => $cita, 'pacientes' => $pacientes, 'medicos' => $medicos, 'medicamentos' => $medicamentos]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCitaRequest $request, Cita $cita)
    {
        $cita->fill($request->validated());
        $cita->save();
        session()->flash('success', 'Cita modificada correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('citas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cita $cita)
    {
        $this->authorize('delete', $cita);
        if($cita->delete())
            session()->flash('success', 'Cita borrado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        else
            session()->flash('warning', 'La cita no pudo borrarse. Es probable que se deba a que tenga asociada información como citas que dependen de él.');
        return redirect()->route('citas.index');
    }

    public function attach_medicamento(Request $request, Cita $cita)
    {
        // Valido en el controlador directamente en vez de en una form request separada porque necesito validar añadiendo un nombre al bag de errores.
        // Necesito añadir un nombre al bag de attach porque la vista de edit tiene 2 formularios, cada uno pudiento tener errores de validación, así que asociamos un nombre a uno de ellos para poder diferenciar
        // En la vista accederemos a los errores de validación de este formulario a través del nombre del bag
        $this->validateWithBag('attach', $request, [
            'medicamento_id' => 'required|exists:medicos,id',
            'inicio' => 'required|date',
            'fin' => 'required|date|after:inicio',
            'comentarios' => 'nullable|string',
            'tomas_dia' => 'required|numeric|min:0',
        ]);
        $cita->medicamentos()->attach($request->medicamento_id, [
            'inicio' => $request->inicio,
            'fin' => $request->fin,
            'comentarios' => $request->comentarios,
            'tomas_dia' => $request->tomas_dia
        ]);
        return redirect()->route('citas.edit', $cita->id);
    }

    public function detach_medicamento(Cita $cita, Medicamento $medicamento)
    {
        $cita->medicamentos()->detach($medicamento->id);
        return redirect()->route('citas.edit', $cita->id);
    }
}
