<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Tratamiento;
use App\Models\Medicamento;
use App\Models\Paciente;
use App\Models\Diagnostico;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Tratamiento\StoreTratamientoRequest;
use App\Http\Requests\Tratamiento\UpdateTratamientoRequest;
use App\Services\InferenciaTratamientoService;
use Carbon\Carbon;

class TratamientoController extends Controller
{
    private InferenciaTratamientoService $inferenciaTratamientoService;

    public function __construct(InferenciaTratamientoService $inferenciaTratamientoService)
    {
        $this->inferenciaTratamientoService = $inferenciaTratamientoService;
    }

    public function index()
    {
        $this->authorize('viewAny', Tratamiento::class);

        $user = Auth::user();

        $with = [
            'paciente',
            'paciente.usuarioAcceso',
            'medico',
            'medico.user',
            'lineasTratamiento'
        ];

        if ($user->es_medico) {
            $tratamientos = $user->medico
                ->tratamientos()
                ->with($with)
                ->latest('fecha_asignacion')
                ->paginate(25);

        } elseif ($user->es_paciente) {
            $tratamientos = $user->paciente
                ->tratamientos()
                ->with($with)
                ->latest('fecha_asignacion')
                ->paginate(25);

        } else {
            $tratamientos = Tratamiento::with($with)
                ->latest('fecha_asignacion')
                ->paginate(25);
        }

        return view('tratamientos.index', compact('tratamientos'));
    }

    public function create()
    {
        $this->authorize('create', Tratamiento::class);

        $user = Auth::user();

        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            if (!$medicoId) {
                abort(403);
            }

            return view('tratamientos.create', [
                'medico' => $user->medico,
                'pacientes' => Paciente::query()
                    ->with('usuarioAcceso')
                    ->where('medico_id', $medicoId)
                    ->orderByDesc('id')
                    ->get(),
            ]);
        }

        if ($user->es_paciente) {
            return view('tratamientos.create', [
                'paciente' => $user->paciente,
                'medicos' => Medico::with('user')->get()
            ]);
        }

        return view('tratamientos.create', [
            'pacientes' => Paciente::with('usuarioAcceso')->orderByDesc('id')->get(),
            'medicos' => Medico::with('user')->get(),
        ]);
    }

    public function store(StoreTratamientoRequest $request)
    {
        $this->authorize('create', Tratamiento::class);

        $data = $request->validated();
        $user = Auth::user();

        if (empty($data['fecha_asignacion'])) {
            $data['fecha_asignacion'] = now()->toDateString();
        }

        if ($user->es_medico) {
            $data['medico_id'] = $user->medico->id;

            if (!empty($data['paciente_id'])) {
                $paciente = Paciente::find((int) $data['paciente_id']);
                if (!$paciente) {
                    abort(404);
                }
                $this->authorize('view', $paciente);
            }
        }

        if ($user->es_paciente) {
            $data['paciente_id'] = $user->paciente->id;
        }

        Tratamiento::create($data);

        session()->flash('success', 'Tratamiento creado correctamente.');
        return redirect()->route('tratamientos.index');
    }

    public function show(Tratamiento $tratamiento)
    {
        $this->authorize('view', $tratamiento);

        $tratamiento->loadMissing([
            'paciente.usuarioAcceso',
            'medico.user',
            'lineasTratamiento',
        ]);

        return view('tratamientos.show', compact('tratamiento'));
    }

    public function edit(Tratamiento $tratamiento)
    {
        $this->authorize('update', $tratamiento);

        $tratamiento->loadMissing(['paciente.usuarioAcceso', 'lineasTratamiento']);

        return view('tratamientos.edit', [
            'tratamiento' => $tratamiento,
            'pacientes' => [],
            'pacienteSeleccionado' => $tratamiento->paciente,
        ]);
    }

    public function update(UpdateTratamientoRequest $request, Tratamiento $tratamiento)
    {
        $this->authorize('update', $tratamiento);

        $data = $request->validated();
        $user = Auth::user();

        if (empty($data['fecha_asignacion'])) {
            $data['fecha_asignacion'] = $tratamiento->fecha_asignacion ?? now()->toDateString();
        }

        unset($data['medico_id'], $data['paciente_id']);

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

    public function destroy(Tratamiento $tratamiento)
    {
        $this->authorize('delete', $tratamiento);

        $tratamiento->delete();

        session()->flash('success', 'Registro borrado correctamente.');
        return redirect()->route('tratamientos.index');
    }

    public function inferir_desde_diagnostico(Diagnostico $diagnostico)
    {
        $this->authorize('create', Tratamiento::class);

        $user = Auth::user();
        if (!$user->es_medico) {
            abort(403);
        }

        $this->authorize('view', $diagnostico);

        $existente = Tratamiento::query()
            ->where('diagnostico_id', $diagnostico->id)
            ->latest('id')
            ->first();

        if ($existente) {
            return redirect()
                ->route('diagnosticos.show', $diagnostico->id)
                ->with('warning', 'Ya existe un tratamiento inferido para este diagnóstico.')
                ->with('tratamiento_existente_id', $existente->id);
        }

        $trat = $this->inferenciaTratamientoService->inferirDesdeDiagnostico($diagnostico, $user->medico->id);

        if (!$trat) {
            session()->flash('warning', 'No se ha inferido tratamiento: el diagnóstico no cumple criterios o no existe regla aplicable.');
            return back();
        }

        session()->flash('success', 'Tratamiento inferido correctamente.');
        return redirect()->route('tratamientos.show', $trat->id);
    }

    public function attach_medicamento(Request $request, Tratamiento $tratamiento)
    {
        $this->authorize('update', $tratamiento);

        $this->validateWithBag('attach', $request, [
            'medicamento_id' => 'required|exists:medicamentos,id',
            'fecha_ini_linea' => 'required|date',
            'fecha_fin_linea' => 'nullable|date|after:fecha_ini_linea',
            'fecha_resp_linea' => 'required|date|after:fecha_ini_linea',
            'observaciones' => 'nullable|string',
            'tomas' => 'required|numeric|min:0',
        ]);

        $fechaIni = Carbon::parse($request->fecha_ini_linea);

        $duracionLinea = null;
        if ($request->filled('fecha_fin_linea')) {
            $fechaFin = Carbon::parse($request->fecha_fin_linea);
            $duracionLinea = $fechaFin->diffInDays($fechaIni);
        }

        $tratamiento->lineasTratamiento()->attach($request->medicamento_id, [
            'fecha_ini_linea' => $request->fecha_ini_linea,
            'fecha_fin_linea' => $request->fecha_fin_linea,
            'fecha_resp_linea' => $request->fecha_resp_linea,
            'observaciones' => $request->observaciones,
            'tomas' => $request->tomas,
            'duracion_linea' => $duracionLinea,
        ]);

        return redirect()
            ->to(route('tratamientos.edit', $tratamiento->id) . '#lineas-tratamiento')
            ->with('success_linea', 'Línea añadida correctamente.');
    }

    public function detach_medicamento(Tratamiento $tratamiento, Medicamento $medicamento)
    {
        $this->authorize('update', $tratamiento);

        $tratamiento->lineasTratamiento()->detach($medicamento->id);

        return redirect()
            ->to(route('tratamientos.edit', $tratamiento->id) . '#lineas-tratamiento')
            ->with('success_linea', 'Línea de tratamiento eliminada correctamente.');
    }

    public function cerrar_linea(Request $request, Tratamiento $tratamiento)
    {
        $this->authorize('update', $tratamiento);

        $this->validateWithBag('cerrar', $request, [
            'medicamento_id' => 'required|exists:medicamentos,id',
            'fecha_fin_linea' => 'nullable|date',
        ]);

        $medicamentoId = (int) $request->medicamento_id;

        $fechaFin = $request->filled('fecha_fin_linea')
            ? Carbon::parse($request->fecha_fin_linea)->toDateString()
            : now()->toDateString();

        $pivot = $tratamiento->lineasTratamiento()
            ->wherePivot('medicamento_id', $medicamentoId)
            ->first();

        if (!$pivot) {
            return back()->with('error_linea', 'No se encontró la línea de tratamiento para ese medicamento.');
        }

        $fechaIni = $pivot->pivot->fecha_ini_linea;

        if (!$fechaIni) {
            return back()->with('error_linea', 'La línea no tiene fecha_ini_linea; no se puede cerrar de forma consistente.');
        }

        if (Carbon::parse($fechaFin)->lt(Carbon::parse($fechaIni))) {
            return back()->with('error_linea', 'fecha_fin_linea no puede ser anterior a fecha_ini_linea.');
        }

        $duracionLinea = Carbon::parse($fechaFin)->diffInDays(Carbon::parse($fechaIni));

        $tratamiento->lineasTratamiento()->updateExistingPivot($medicamentoId, [
            'fecha_fin_linea' => $fechaFin,
            'duracion_linea' => $duracionLinea,
        ]);

        return redirect()
            ->to(route('tratamientos.edit', $tratamiento->id) . '#lineas-tratamiento')
            ->with('success_linea', 'Línea de tratamiento cerrada correctamente.');
    }
}
