<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Tratamiento;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Enfermedad;
use App\Models\Diagnostico;
use App\Models\Sintoma;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Diagnostico\StoreDiagnosticoRequest;
use App\Http\Requests\Diagnostico\UpdateDiagnosticoRequest;
use App\Models\Comienzo;
use App\Models\Estado;
use App\Models\Infeccion;
use App\Services\InferenciaDiagnosticoService;
use Carbon\Carbon;

class DiagnosticoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Diagnostico::class);
        $diagnosticos = Diagnostico::paginate(25);
        return view('/diagnosticos/index', ['diagnosticos' => $diagnosticos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Diagnostico::class);

        // Verifica si el usuario está autenticado
        $usuario = Auth::user();

        // valida si el usuario es paciente o médico
        $esPaciente = $usuario->es_paciente;
        $esMedico = $usuario->es_medico;

        // Lista de pacientes (solo si el usuario autenticado no es paciente)
        $pacientes = Paciente::all();

        // Lista de enfermedades
        $enfermedades = Enfermedad::all();

        // Lista de síntomas
        $sintomas = Sintoma::all();

        // Si el usuario es un médico, obtener sus pacientes
        $medico = $esMedico ? $usuario->medico : null;
        $estados = Estado::all();
        $comienzos = Comienzo::all();
        $infeccions = Infeccion::all();

        return view('diagnosticos.create', compact(['sintomas' => $sintomas, 'comienzos' => $comienzos, 'estados' => $estados, 'infeccions' => $infeccions, 'diasDesdeTrasplante']));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiagnosticoRequest $request)
    {
        /*$diagnostico = new Diagnostico($request->validated());
        $diagnostico->save();
        session()->flash('success', 'diagnostico creado correctamente.');
        return redirect()->route('diagnosticos.index');*/

        /* Validar los datos usando el Request
        $validatedData = $request->validated();
        //Calcular días desde trasplante si se proporcionó fecha
        if ($request->filled('f_trasplante')) {
            $validatedData['dias_desde_trasplante'] = Carbon::parse($request->f_trasplante)->diffInDays(now());
        }

        // Crear el diagnóstico
        $diagnostico = Diagnostico::create($validatedData);

          Asociar síntomas al diagnóstico (si se proporcionan)
        if ($request->has('sintomas')) {
            foreach ($request->sintomas as $sintomaId => $sintomaData) {
                $diagnostico->sintomas()->attach($sintomaId, [
                    'fecha_diagnostico' => $sintomaData['fecha_diagnostico'],
                    'score_nih' => $sintomaData['score_nih'],
                ]);
            }
        }*/

        // Redirigir con un mensaje de éxito
        return redirect()->route('diagnosticos.index')->with('success', 'Diagnóstico creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Diagnostico $diagnostico)
    {
        $this->authorize('view', $diagnostico);
        //$sintomas = $diagnostico->sintomas;
        return view('diagnosticos/show', ['diagnostico' => $diagnostico]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diagnostico $diagnostico)
    {
        $this->authorize('update', $diagnostico);
        $sintomas = Sintoma::all();
        $estados = Estado::all();
        $comienzos = Comienzo::all();
        $infeccions = Infeccion::all();
        return view('diagnosticos/edit', ['diagnostico' => $diagnostico, 'sintomas' => $sintomas, 'estados' => $estados, 'comienzos' => $comienzos, 'infeccions' => $infeccions]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiagnosticoRequest $request, Diagnostico $diagnostico)
    {
        $this->authorize('update', $diagnostico);
        $diagnostico->fill($request->validated());
        $diagnostico->save();
        session()->flash('success', 'Registro modificado correctamente.');
        return redirect()->route('diagnosticos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diagnostico $diagnostico)
    {
        $this->authorize('delete', $diagnostico);
        if ($diagnostico->delete())
            session()->flash('success', 'Registro borrado correctamente.');
        else
            session()->flash('warning', 'No pudo borrarse el registro.');
        return redirect()->route('diagnosticos.index');
    }

    /**
     * Adjunta un síntoma al diagnóstico.
     */
    public function attach_Sintoma(Request $request, Diagnostico $diagnostico): RedirectResponse
    {
        // Validación con un nombre de error bag específico ('attach')
        $this->validateWithBag('attach', $request, [
            'sintoma_id' => 'required|exists:sintomas,id',
            'fecha_diagnostico' => 'required|date',
            'score_nih' => 'required|integer|min:1|max:12',
        ]);

        // Adjuntar el síntoma al diagnóstico con los datos de la tabla pivote
        $diagnostico->sintomas()->attach($request->sintoma_id, [
            'fecha_diagnostico' => $request->fecha_diagnostico,
            'score_nih' => $request->score_nih,
        ]);

        // Redirigir a la vista de edición del diagnóstico
        return redirect()->route('diagnosticos.edit', $diagnostico->id);
    }

    /**
     * Desvincula un síntoma del diagnóstico.
     */
    public function detach_Sintoma(Diagnostico $diagnostico, Sintoma $sintoma): RedirectResponse
    {
        //Desvincular el síntoma del diagnóstico
        $diagnostico->sintomas()->detach($sintoma->id);

        //Redirigir a la vista de edición del diagnóstico
        return redirect()->route('diagnosticos.edit', $diagnostico->id);
    }

    public function inferirDesdeSistema($pacienteId, InferenciaDiagnosticoService $inferenciaService)
    {
        $paciente = Paciente::find($pacienteId);

        if (!$paciente) {
            return redirect()->back()->with('warning', 'Paciente no encontrado.');
        }

        //debuggear cuando se llama a esta función
        $diagnostico = $inferenciaService->ejecutar($paciente);

        if ($diagnostico) {
            return redirect()->route('diagnosticos.show', $diagnostico->id)
                ->with('success', 'Diagnóstico inferido correctamente.');
        } else {
            return redirect()->back()->with('warning', 'No se ha podido inferir ningún diagnóstico para este paciente.');
        }
    }

    public function inferidos()
    {
        $this->authorize('viewAny', Diagnostico::class);

        $diagnosticos = Diagnostico::whereHas('sintomas', function ($query) {
            $query->wherePivot('origen', 'Inferido');
        })->paginate(25);

        return view('diagnosticos.index', [
            'diagnosticos' => $diagnosticos,
            'soloInferidos' => true // Flag para distinguir en la vista
        ]);
    }
}
