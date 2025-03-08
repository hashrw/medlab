<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Tratamiento;
use App\Models\Diagnostico;
use App\Models\Sintoma;
use App\Models\User;
use App\Http\Requests\Diagnostico\StoreDiagnosticoRequest;
use App\Http\Requests\Diagnostico\UpdateDiagnosticoRequest;


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
        $this->authorize('create', diagnostico::class);
        return view('diagnosticos/create');
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

        // Validar los datos usando el Request
        $validatedData = $request->validated();

        // Crear el diagnóstico
        $diagnostico = Diagnostico::create($validatedData);

        // Asociar síntomas al diagnóstico (si se proporcionan)
        if ($request->has('sintomas')) {
            foreach ($request->sintomas as $sintomaId => $sintomaData) {
                $diagnostico->sintomas()->attach($sintomaId, [
                    'fecha_diagnostico' => $sintomaData['fecha_diagnostico'],
                    'score_nih' => $sintomaData['score_nih'],
                ]);
            }
        }

        // Redirigir con un mensaje de éxito
        return redirect()->route('diagnosticos.index')->with('success', 'Diagnóstico creado correctamente.');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Diagnostico $diagnostico)
    {
        $this->authorize('view', $diagnostico);
        return view('diagnosticos/show', ['diagnostico' => $diagnostico]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diagnostico $diagnostico)
    {
        $this->authorize('update', $diagnostico);
        return view('diagnosticos/edit', ['diagnostico' => $diagnostico]);
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
        if($diagnostico->delete())
            session()->flash('success', 'Registro borrado correctamente.');
        else
            session()->flash('warning', 'No pudo borrarse el registro.');
        return redirect()->route('diagnosticos.index');
    }

    /**
     * Adjunta un síntoma al diagnóstico.
     */
    public function attachSintoma(Request $request, Diagnostico $diagnostico): RedirectResponse
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
    public function detachSintoma(Diagnostico $diagnostico, Sintoma $sintoma): RedirectResponse
    {
        // Desvincular el síntoma del diagnóstico
        $diagnostico->sintomas()->detach($sintoma->id);

        // Redirigir a la vista de edición del diagnóstico
        return redirect()->route('diagnosticos.edit', $diagnostico->id);
    }
}
