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
use App\Models\ReglaDecision;
use App\Services\InferenciaDiagnosticoService;
use Carbon\Carbon;

class DiagnosticoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Diagnostico::class);

        // cargamos la relación 'regla' y 'estado'
        $diagnosticos = Diagnostico::with(['regla', 'estado', 'paciente.usuarioAcceso'])->paginate(25);

        return view('diagnosticos.index', ['diagnosticos' => $diagnosticos]);
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
        //$enfermedades = Enfermedad::all();

        // Lista de síntomas
        $sintomas = Sintoma::all();

        // Si el usuario es un médico, obtener sus pacientes
        $medico = $esMedico ? $usuario->medico : null;
        $estados = Estado::all();
        $comienzos = Comienzo::all();
        $infeccions = Infeccion::all();
        //diasDesdeTrasplante alamcenar en Enfermedad (Ficha de Trasplante)

        //return view('diagnosticos.create', compact(['sintomas' => $sintomas, 'comienzos' => $comienzos, 'estados' => $estados, 'infeccions' => $infeccions, 'diasDesdeTrasplante']));
        return view('diagnosticos.create', compact(
            'sintomas',
            'comienzos',
            'estados',
            'infeccions',
            'pacientes',
        ));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiagnosticoRequest $request)
    {
        $this->authorize('create', Diagnostico::class);

        $validated = $request->validated();

        // Determinar paciente_id (1:N) según rol
        $pacienteId = null;

        if ($request->user()->es_medico) {
            // StoreDiagnosticoRequest ya obliga paciente_id para médico
            $pacienteId = (int) $validated['paciente_id'];
        } elseif ($request->user()->es_paciente && $request->user()->paciente_id) {
            // Si en el futuro permites que el paciente cree diagnósticos: se toma de su user->paciente_id
            $pacienteId = (int) $request->user()->paciente_id;
        }

        // Datos propios del diagnóstico (sin relaciones pivot/control)
        $datosDiagnostico = collect($validated)->except([
            'sintomas',
            'paciente_id',
        ])->toArray();

        // Fecha por defecto si no viene
        if (empty($datosDiagnostico['fecha_diagnostico'])) {
            $datosDiagnostico['fecha_diagnostico'] = now()->toDateString();
        }

        // Asignar paciente_id en la tabla diagnosticos (1:N)
        $datosDiagnostico['paciente_id'] = $pacienteId;

        // Crear diagnóstico
        $diagnostico = Diagnostico::create($datosDiagnostico);

        // Asociar síntomas (pivot diagnostico_sintoma)
        if (!empty($validated['sintomas'])) {
            foreach ($validated['sintomas'] as $sintomaId => $datos) {
                $diagnostico->sintomas()->attach((int) $sintomaId, [
                    'fecha_diagnostico' => $datos['fecha_diagnostico'] ?? now(),
                    'score_nih' => $datos['score_nih'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('diagnosticos.index')
            ->with('success', 'Diagnóstico creado correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Diagnostico $diagnostico)
    {
        $this->authorize('view', $diagnostico);

        $diagnostico->load([
            'regla',
            'origen',
            'estado',
            'comienzo',
            'infeccion',
            'sintomas',
            'paciente',
        ]);

        $paciente = $diagnostico->paciente;

        $ultimoTrasplante = $paciente
            ? $paciente->trasplantes()->orderByDesc('fecha_trasplante')->first()
            : null;

        $diasDesdeTrasplante = $ultimoTrasplante?->dias_desde_trasplante;

        return view('diagnosticos.show', [
            'diagnostico' => $diagnostico,
            'paciente' => $paciente,
            'ultimoTrasplante' => $ultimoTrasplante,
            'diasDesdeTrasplante' => $diasDesdeTrasplante,
        ]);
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

        $validated = $request->validated();

        // Nunca vamos a guardar medico_id en diagnóstico
        unset($validated['medico_id'], $validated['sintomas']);

        // Si el diagnóstico es inferido (viene de una regla), limitamos campos editables
        if ($diagnostico->regla_decision_id) {
            $permitidos = [
                'estado_injerto',
                'observaciones',
                'grado_eich',
                'escala_karnofsky',
                'estado_id',
                'comienzo_id',
                'infeccion_id',
            ];

            $datosActualizables = array_intersect_key(
                $validated,
                array_flip($permitidos)
            );

            $diagnostico->fill($datosActualizables);
        } else {
            // Diagnóstico manual: podemos usar todo lo validado (menos lo que hemos quitado arriba)
            $diagnostico->fill($validated);
        }

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
        // No permitir modificar síntomas de diagnósticos inferidos
        if ($diagnostico->regla_decision_id) {
            return redirect()
                ->route('diagnosticos.edit', $diagnostico->id)
                ->with('warning', 'No se pueden modificar los síntomas de un diagnóstico inferido.');
        }

        $this->validateWithBag('attach', $request, [
            'sintoma_id' => 'required|exists:sintomas,id',
            'fecha_diagnostico' => 'required|date',
            'score_nih' => 'required|integer|min:1|max:12',
        ]);

        $diagnostico->sintomas()->attach($request->sintoma_id, [
            'fecha_diagnostico' => $request->fecha_diagnostico,
            'score_nih' => $request->score_nih,
        ]);

        return redirect()->route('diagnosticos.edit', $diagnostico->id);
    }

    public function detach_Sintoma(Diagnostico $diagnostico, Sintoma $sintoma): RedirectResponse
    {
        if ($diagnostico->regla_decision_id) {
            return redirect()
                ->route('diagnosticos.edit', $diagnostico->id)
                ->with('warning', 'No se pueden modificar los síntomas de un diagnóstico inferido.');
        }

        $diagnostico->sintomas()->detach($sintoma->id);

        return redirect()->route('diagnosticos.edit', $diagnostico->id);
    }


    public function inferirDesdeSistema($pacienteId, InferenciaDiagnosticoService $inferenciaService)
    {
        $paciente = Paciente::find($pacienteId);

        if (!$paciente) {
            return redirect()->back()->with('warning', 'Paciente no encontrado.');
        }

        $diagnostico = $inferenciaService->ejecutar($paciente);

        if ($diagnostico) {
            $regla = $diagnostico->regla_decision_id
                ? ReglaDecision::find($diagnostico->regla_decision_id)
                : null;

            $mensaje = 'Diagnóstico inferido correctamente.';
            if ($regla && $regla->tipo_recomendacion) {
                $mensaje .= ' Recomendación: ' . $regla->tipo_recomendacion . '.';
            }

            return redirect()->route('diagnosticos.show', $diagnostico->id)
                ->with('success', $mensaje);
        }

        return redirect()->back()->with('warning', 'No se ha podido inferir ningún diagnóstico para este paciente.');
    }

    public function inferidos()
    {
        $this->authorize('viewAny', Diagnostico::class);

        $diagnosticos = Diagnostico::with(['regla', 'estado'])
            ->whereNotNull('regla_decision_id')
            ->paginate(25);

        return view('diagnosticos.index', [
            'diagnosticos' => $diagnosticos,
            'soloInferidos' => true,
        ]);
    }

    public function inferirSelector(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $pacientes = Paciente::query()
            ->with('usuarioAcceso') // para mostrar nombre/email sin N+1
            ->when($q !== '', function ($query) use ($q) {

                $query->where(function ($sub) use ($q) {

                    // ID exacto si es numérico
                    if (ctype_digit($q)) {
                        $sub->orWhere('id', (int) $q);
                    }

                    // NUHSA en pacientes
                    $sub->orWhere('nuhsa', 'like', "%{$q}%");

                    // Nombre en usuarioAcceso (user)
                    $sub->orWhereHas('usuarioAcceso', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('diagnosticos.inferir_selector', compact('pacientes', 'q'));
    }

}
