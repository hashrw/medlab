<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Tratamiento;
use App\Models\Medicamento;
use App\Models\Paciente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Tratamiento\StoreTratamientoRequest;
use App\Http\Requests\Tratamiento\UpdateTratamientoRequest;

class TratamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Tratamiento::class);

        $user = Auth::user();

        // Carga correcta según la nueva arquitectura
        $with = [
            'paciente',          // Paciente clínico
            'paciente.usuarioAcceso', // Usuario del paciente (si existe)
            'medico',
            'medico.user',       // Usuario del médico
            'lineasTratamiento'
        ];

        if ($user->es_medico) {

            $tratamientos = $user->medico
                ->tratamientos()
                ->with($with)
                ->latest('fecha_asignacion')
                ->paginate(25);

        } elseif ($user->es_paciente) {

            // Ahora el usuario → pertenece al Paciente
            $tratamientos = $user->paciente
                ->tratamientos()
                ->with($with)
                ->latest('fecha_asignacion')
                ->paginate(25);

        } else {

            // Admin o roles especiales
            $tratamientos = Tratamiento::with($with)
                ->latest('fecha_asignacion')
                ->paginate(25);
        }

        return view('tratamientos.index', compact('tratamientos'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Tratamiento::class);

        $user = Auth::user();

        if ($user->es_medico) {
            return view('tratamientos.create', [
                'medico' => $user->medico,
                'pacientes' => Paciente::all(),
            ]);
        }

        if ($user->es_paciente) {
            return view('tratamientos.create', [
                'paciente' => $user->paciente,
                'medicos' => Medico::with('user')->get()
            ]);
        }

        return view('tratamientos.create', [
            'pacientes' => Paciente::all(),
            'medicos' => Medico::with('user')->get(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTratamientoRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        // Forzar integridad de medico/paciente según rol
        if ($user->es_medico) {
            $data['medico_id'] = $user->medico->id;
        }

        if ($user->es_paciente) {
            $data['paciente_id'] = $user->paciente->id;
        }

        Tratamiento::create($data);

        session()->flash('success', 'Tratamiento creado correctamente.');
        return redirect()->route('tratamientos.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Tratamiento $tratamiento)
    {
        $this->authorize('view', $tratamiento);

        // Si necesitamos mostrar lista de pacientes (p.ej para asignación secundaria):
        $pacientes = Paciente::with('usuarioAcceso')->get();

        return view('tratamientos.show', compact('tratamiento', 'pacientes'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tratamiento $tratamiento)
    {
        $this->authorize('update', $tratamiento);

        return view('tratamientos.edit', [
            'tratamiento' => $tratamiento,
            'medicos' => Medico::with('user')->get(),
            'pacientes' => Paciente::with('usuarioAcceso')->get(),
            'medicamentos' => Medicamento::all(),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTratamientoRequest $request, Tratamiento $tratamiento)
    {
        $this->authorize('update', $tratamiento);

        $data = $request->validated();
        $user = Auth::user();

        // No permitir reasignar médico/paciente si el usuario NO es admin
        if ($user->es_medico) {
            $data['medico_id'] = $tratamiento->medico_id;
        }

        if ($user->es_paciente) {
            $data['paciente_id'] = $tratamiento->paciente_id;
        }

        $tratamiento->update($data);

        session()->flash('success', 'Registro modificado correctamente.');
        return redirect()->route('tratamientos.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tratamiento $tratamiento)
    {
        $this->authorize('delete', $tratamiento);

        $tratamiento->delete();

        session()->flash('success', 'Registro borrado correctamente.');
        return redirect()->route('tratamientos.index');
    }


    /**
     * Añadir línea de tratamiento desde formulario pivot.
     */
    public function attach_medicamento(Request $request, Tratamiento $tratamiento)
    {
        $this->authorize('update', $tratamiento);

        $this->validateWithBag('attach', $request, [
            'medicamento_id' => 'required|exists:medicamentos,id',
            'fecha_ini_linea' => 'required|date',
            'fecha_fin_linea' => 'required|date|after:fecha_ini_linea',
            'fecha_resp_linea' => 'required|date|after:fecha_ini_linea',
            'observaciones' => 'nullable|string',
            'tomas' => 'required|numeric|min:0',
            'duracion_linea' => 'required|numeric|min:0',
        ]);

        $tratamiento->lineasTratamiento()->attach($request->medicamento_id, [
            'fecha_ini_linea' => $request->fecha_ini_linea,
            'fecha_fin_linea' => $request->fecha_fin_linea,
            'fecha_resp_linea' => $request->fecha_resp_linea,
            'observaciones' => $request->observaciones,
            'tomas' => $request->tomas,
            'duracion_linea' => $request->duracion_linea,
        ]);

        return redirect()->route('tratamientos.edit', $tratamiento->id);
    }


    public function detach_medicamento(Tratamiento $tratamiento, Medicamento $medicamento)
    {
        $this->authorize('update', $tratamiento);

        $tratamiento->lineasTratamiento()->detach($medicamento->id);

        return redirect()->route('tratamientos.edit', $tratamiento->id);
    }
}
