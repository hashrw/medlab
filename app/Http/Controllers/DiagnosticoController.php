<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Diagnostico;
use App\Models\Sintoma;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Diagnostico\StoreDiagnosticoRequest;
use App\Http\Requests\Diagnostico\UpdateDiagnosticoRequest;
use App\Models\Comienzo;
use App\Models\Estado;
use App\Models\Infeccion;
use App\Models\ReglaDecision;
use App\Services\InferenciaDiagnosticoService;
use App\Services\Documental\EvidenciaClientService;
use App\Jobs\GenerateClinicalReportJob;
use App\Models\InformeClinico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DiagnosticoController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Diagnostico::class);

        $user = Auth::user();

        $query = Diagnostico::query()
            ->with(['regla', 'estado', 'paciente.usuarioAcceso']);

        // P0: scoping por rol
        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            if (!$medicoId) {
                abort(403);
            }

            $query->whereHas('paciente', function ($q) use ($medicoId) {
                $q->where('medico_id', $medicoId);
            });
        } elseif ($user->es_paciente) {
            $pacienteId = $user->paciente?->id;
            if (!$pacienteId) {
                abort(403);
            }

            $query->where('paciente_id', $pacienteId);
        }

        $diagnosticos = $query
            ->paginate(25)
            ->appends($request->query());

        return view('diagnosticos.index', ['diagnosticos' => $diagnosticos]);
    }

    public function create()
    {
        $this->authorize('create', Diagnostico::class);

        $usuario = Auth::user();

        $esPaciente = $usuario->es_paciente;
        $esMedico = $usuario->es_medico;

        // P0: evitar fuga de pacientes
        $pacientes = collect();

        if (!$esPaciente) {
            $pacientesQuery = Paciente::query()->with('usuarioAcceso');

            if ($esMedico) {
                $medicoId = $usuario->medico?->id;
                if (!$medicoId) {
                    abort(403);
                }

                $pacientesQuery->where('medico_id', $medicoId);
            }

            $pacientes = $pacientesQuery->orderByDesc('id')->get();
        }

        $sintomas = Sintoma::all();
        $estados = Estado::all();
        $comienzos = Comienzo::all();
        $infeccions = Infeccion::all();

        return view('diagnosticos.create', compact(
            'sintomas',
            'comienzos',
            'estados',
            'infeccions',
            'pacientes',
        ));
    }

    public function store(StoreDiagnosticoRequest $request)
    {
        $this->authorize('create', Diagnostico::class);

        $validated = $request->validated();

        $pacienteId = null;

        if ($request->user()->es_medico) {
            $pacienteId = (int) $validated['paciente_id'];

            // P0: bloquear médico creando para paciente ajeno
            $paciente = Paciente::find($pacienteId);
            if (!$paciente) {
                abort(404);
            }
            $this->authorize('view', $paciente);
        } elseif ($request->user()->es_paciente && $request->user()->paciente_id) {
            $pacienteId = (int) $request->user()->paciente_id;
        }

        $datosDiagnostico = collect($validated)->except([
            'sintomas',
            'paciente_id',
        ])->toArray();

        if (empty($datosDiagnostico['fecha_diagnostico'])) {
            $datosDiagnostico['fecha_diagnostico'] = now()->toDateString();
        }

        $datosDiagnostico['paciente_id'] = $pacienteId;

        $diagnostico = Diagnostico::create($datosDiagnostico);

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

    public function show(Diagnostico $diagnostico)
    {
        //dd('ENTRA EN SHOW', $diagnostico->id);
        //$this->authorize('view', $diagnostico);

        $diagnostico->load([
            'regla',
            'origen',
            'estado',
            'comienzo',
            'infeccion',
            'sintomas',
            'paciente',
            'informesClinicos'
        ]);

        $prev = url()->previous();

        $paciente = $diagnostico->paciente;

        $ultimoTrasplante = $paciente
            ? $paciente->trasplantes()->orderByDesc('fecha_trasplante')->first()
            : null;

        $diasDesdeTrasplante = $ultimoTrasplante?->dias_desde_trasplante;

        // NUEVO (incremental): últimos registros para "Información Clínica Asociada"
        $trasplantes = $paciente
            ? $paciente->trasplantes()->orderByDesc('fecha_trasplante')->limit(5)->get()
            : collect();

        // Ajusta este bloque según tu modelado real:
        // Opción A: pruebas dependen del diagnóstico (diagnostico_id)
        $pruebas = method_exists($diagnostico, 'pruebasClinicas')
            ? $diagnostico->pruebas()->orderByDesc('fecha')->limit(5)->get()
            : collect();

        $ultimoInformeClinico = $diagnostico->informesClinicos()
            ->latest()
            ->first();

        if (!str_contains($prev, route('diagnosticos.show', $diagnostico->id))) {
            session(['diagnosticos_back_url' => $prev]);
        }

        return view('diagnosticos.show', [
            'diagnostico' => $diagnostico,
            'paciente' => $paciente,
            'ultimoTrasplante' => $ultimoTrasplante,
            'diasDesdeTrasplante' => $diasDesdeTrasplante,
            'trasplantes' => $trasplantes,   // NUEVO
            'pruebas' => $pruebas,           // NUEVO
            'ultimoInformeClinico' => $ultimoInformeClinico,
        ]);
    }

    public function edit(Diagnostico $diagnostico)
    {
        $this->authorize('update', $diagnostico);

        $sintomas = Sintoma::all();
        $estados = Estado::all();
        $comienzos = Comienzo::all();
        $infeccions = Infeccion::all();

        return view('diagnosticos/edit', [
            'diagnostico' => $diagnostico,
            'sintomas' => $sintomas,
            'estados' => $estados,
            'comienzos' => $comienzos,
            'infeccions' => $infeccions
        ]);
    }

    public function update(UpdateDiagnosticoRequest $request, Diagnostico $diagnostico)
    {
        $this->authorize('update', $diagnostico);

        $validated = $request->validated();

        unset($validated['medico_id'], $validated['sintomas']);

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
            $diagnostico->fill($validated);
        }

        $diagnostico->save();

        session()->flash('success', 'Registro modificado correctamente.');

        return redirect()->route('diagnosticos.index');
    }

    public function destroy(Diagnostico $diagnostico)
    {
        $this->authorize('delete', $diagnostico);

        if ($diagnostico->delete()) {
            session()->flash('success', 'Registro borrado correctamente.');
        } else {
            session()->flash('warning', 'No pudo borrarse el registro.');
        }

        return redirect()->route('diagnosticos.index');
    }

    public function attach_Sintoma(Request $request, Diagnostico $diagnostico): RedirectResponse
    {
        if ($diagnostico->regla_decision_id) {
            return redirect()
                ->route('diagnosticos.edit', $diagnostico->id)
                ->with('warning', 'No se pueden modificar los síntomas de un diagnóstico inferido.');
        }

        $this->authorize('attach_sintoma', $diagnostico);

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

        $this->authorize('detach_sintoma', $diagnostico);

        $diagnostico->sintomas()->detach($sintoma->id);

        return redirect()->route('diagnosticos.edit', $diagnostico->id);
    }

    public function inferirDesdeSistema($pacienteId, InferenciaDiagnosticoService $inferenciaService)
    {
        $paciente = Paciente::find($pacienteId);

        if (!$paciente) {
            return redirect()->back()->with([
                'warning' => 'paciente_no_encontrado',
                'flash_ctx' => ['paciente_id' => (int) $pacienteId],
            ]);
        }

        $this->authorize('view', $paciente);

        $existente = Diagnostico::query()
            ->where('paciente_id', (int) $pacienteId)
            ->whereNotNull('regla_decision_id')
            ->orderByDesc('fecha_diagnostico')
            ->orderByDesc('id')
            ->first();

        if ($existente) {
            $fechaReferencia = $existente->updated_at
                ?? $existente->created_at
                ?? Carbon::parse($existente->fecha_diagnostico);

            $ultimaModificacionSintomas = DB::table('paciente_sintoma')
                ->where('paciente_id', (int) $pacienteId)
                ->max('updated_at');

            $queryOrganos = DB::table('organo_paciente')
                ->where('paciente_id', (int) $pacienteId);

            $ultimaModificacionOrganos = Schema::hasColumn('organo_paciente', 'updated_at')
                ? $queryOrganos->max('updated_at')
                : $queryOrganos->max('fecha_evaluacion');

            $ultimaModificacionClinica = collect([
                $ultimaModificacionSintomas,
                $ultimaModificacionOrganos,
            ])
                ->filter()
                ->map(fn($fecha) => Carbon::parse($fecha))
                ->max();

            $hayCambiosClinicosPosteriores =
                $ultimaModificacionClinica &&
                $ultimaModificacionClinica->greaterThan($fechaReferencia);

            if (!$hayCambiosClinicosPosteriores) {
                return redirect()
                    ->route('pacientes.show', (int) $pacienteId)
                    ->with([
                        'warning' => 'diagnostico_ya_existe',
                        'flash_ctx' => [
                            'paciente_id' => (int) $pacienteId,
                            'diagnostico_id' => (int) $existente->id,
                            'grado_eich' => (string) ($existente->grado_eich ?? ''),
                        ],
                    ]);
            }
        }

        try {
            [$diagnostico, $fallback] = $inferenciaService->ejecutar($paciente);
        } catch (ValidationException $e) {

            // Prioridad: NIH, luego síntomas
            $msg = $e->errors()['score_nih'][0]
                ?? $e->errors()['sintomas'][0]
                ?? 'No es posible ejecutar la inferencia: precondiciones no cumplidas.';

            return redirect()
                ->route('pacientes.show', (int) $pacienteId)
                ->with([
                    'warning' => $msg,
                    'flash_ctx' => ['paciente_id' => (int) $pacienteId],
                ]);
        }

        if ($diagnostico) {
            $regla = $diagnostico->regla_decision_id
                ? ReglaDecision::find($diagnostico->regla_decision_id)
                : null;

            $mensaje = 'Diagnóstico inferido correctamente.';
            if ($regla && $regla->tipo_recomendacion) {
                $mensaje .= ' Recomendación: ' . $regla->tipo_recomendacion . '.';
            }

            return redirect()
                ->route('diagnosticos.show', $diagnostico->id)
                ->with('success', $mensaje);
        }

        if ($fallback) {
            return redirect()->back()->with([
                'warning' => 'fallback_aplicado',
                'flash_ctx' => [
                    'regla_id' => (int) $fallback->id,
                    'regla_nombre' => (string) $fallback->nombre,
                ],
            ]);
        }

        return redirect()->back()->with([
            'warning' => 'sin_diagnostico',
            'flash_ctx' => ['paciente_id' => (int) $pacienteId],
        ]);
    }

    public function inferidos(Request $request)
    {
        $this->authorize('viewAny', Diagnostico::class);

        $user = Auth::user();

        $query = Diagnostico::query()
            ->with(['regla', 'estado', 'paciente.usuarioAcceso'])
            ->whereNotNull('regla_decision_id');

        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            if (!$medicoId) {
                abort(403);
            }

            $query->whereHas('paciente', function ($q) use ($medicoId) {
                $q->where('medico_id', $medicoId);
            });
        } elseif ($user->es_paciente) {
            $pacienteId = $user->paciente?->id;
            if (!$pacienteId) {
                abort(403);
            }

            $query->where('paciente_id', $pacienteId);
        }

        $diagnosticos = $query
            ->paginate(25)
            ->appends($request->query());

        return view('diagnosticos.index', [
            'diagnosticos' => $diagnosticos,
            'soloInferidos' => true,
        ]);
    }

    // Generar informe clínico DSS-RAG
    public function generarEvidencia(Diagnostico $diagnostico, EvidenciaClientService $client)
    {
        $this->authorize('view', $diagnostico);

        $diagnostico->load([
            'sintomas.aliases',
            'sintomas.organo',
            'paciente.organos',
            'regla',
        ]);

        $paciente = $diagnostico->paciente;

        if (!$paciente) {
            return back()->with('error', 'Diagnóstico sin paciente.');
        }

        $aliases = $diagnostico->sintomas
            ->flatMap(fn($sintoma) => $sintoma->aliases->pluck('alias'))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $organos = $paciente->organos
            ->filter(fn($organo) => $organo->pivot?->score_nih !== null)
            ->mapWithKeys(fn($organo) => [
                $organo->nombre => (int) $organo->pivot->score_nih,
            ])
            ->toArray();

        $payload = [
            'caso_clinico' => [
                'paciente_id' => $paciente->id,
                'active_aliases_canonical' => $aliases,
                'organo_score_nih_by_nombre' => $organos,
            ],
            'resultado_inferencia' => [
                'status' => 'match',
                'diagnostico_id' => $diagnostico->id,
                'tipo_enfermedad' => $diagnostico->tipo_enfermedad,
                'grado_eich' => $diagnostico->grado_eich,
                'estado_injerto' => $diagnostico->estado_injerto,
                'regla_aplicada' => $diagnostico->regla ? [
                    'id' => $diagnostico->regla->id,
                    'nombre' => $diagnostico->regla->nombre,
                    'prioridad' => $diagnostico->regla->prioridad,
                    'recomendacion_clinica' => $diagnostico->regla->recomendacion_clinica,
                ] : null,
            ],
        ];

        /*try {
            $response = $client->generateClinicalReport($payload);
        } catch (\Throwable $e) {
            return back()->with('error', 'Error generando informe clínico: ' . $e->getMessage());
        }*/

        $informeEnCurso = InformeClinico::where('diagnostico_id', $diagnostico->id)
            ->whereIn('status', ['pending', 'processing'])
            ->latest()
            ->first();

        if ($informeEnCurso) {
            return back()->with([
                'warning' => 'Ya hay un informe clínico en generación. Consulte su estado antes de regenerar.',
                'informe_clinico_id' => $informeEnCurso->id,
            ]);
        }

        $informe = InformeClinico::create([
            'diagnostico_id' => $diagnostico->id,
            'paciente_id' => $paciente->id,
            'status' => 'pending',
            'clinical_report' => null,
            'traceability' => null,
            'llm_used' => false,
            'generated_at' => null,
        ]);

        GenerateClinicalReportJob::dispatch($informe->id, $payload);

        return back()->with([
            'success' => 'El informe clínico se está generando.',
            'informe_clinico_id' => $informe->id,
        ]);
    }

    public function estadoInformeClinico(InformeClinico $informeClinico)
    {
        $this->authorize('view', $informeClinico->diagnostico);

        return response()->json([
            'id' => $informeClinico->id,
            'status' => $informeClinico->status,
            'llm_used' => $informeClinico->llm_used,
            'fallback_reason' => $informeClinico->fallback_reason,
            'error_message' => $informeClinico->error_message,
            'finished_at' => optional($informeClinico->finished_at)->toISOString(),
            'redirect_url' => route('diagnosticos.show', $informeClinico->diagnostico_id),
        ]);
    }
}
