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
    public function index(Request $request)
    {
        $this->authorize('viewAny', Cita::class);

        $user = Auth::user();

        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            abort_unless($medicoId, 403);

            $q = Cita::query()
                ->where('medico_id', $medicoId);

            if ($request->filled('estado')) {
                $q->where('estado', $request->estado);
            }

            $q->orderByRaw("CASE WHEN estado = 'pendiente' THEN 0 ELSE 1 END")
                ->orderByDesc('created_at');

            $citas = $q->paginate(25)->withQueryString();

            return view('citas.index_medico', compact('citas'));
        }

        if ($user->es_paciente) {
            $pacienteId = $user->paciente?->id;
            abort_unless($pacienteId, 403);

            $citas = Cita::query()
                ->where('paciente_id', $pacienteId)
                ->orderByDesc('created_at')
                ->paginate(25);

            return view('citas.index_paciente', compact('citas'));
        }

        // Admin
        $citas = Cita::orderByDesc('created_at')->paginate(25);
        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        $this->authorize('create', Cita::class);

        $user = Auth::user();

        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            abort_unless($medicoId, 403);

            // P0: solo mis pacientes
            $pacientes = Paciente::query()
                ->with('usuarioAcceso')
                ->where('medico_id', $medicoId)
                ->orderByDesc('id')
                ->get();

            return view('citas.create', [
                'medico' => $user->medico,
                'pacientes' => $pacientes,
            ]);
        }

        if ($user->es_paciente) {
            // Si el paciente tiene médico asignado, no necesitas Medico::all()
            $medicoAsignado = $user->paciente?->medico;

            return view('citas.create', [
                'paciente' => $user->paciente,
                'medicos' => $medicoAsignado ? collect([$medicoAsignado->load('user')]) : collect([]),
            ]);
        }

        // Admin
        return view('citas.create', [
            'pacientes' => Paciente::with('usuarioAcceso')->orderByDesc('id')->get(),
            'medicos' => Medico::with('user')->get(),
        ]);
    }

    public function store(StoreCitaRequest $request)
    {
        $this->authorize('create', Cita::class);

        $user = Auth::user();
        $data = $request->validated();

        if ($user->es_paciente) {
            $pacienteId = $user->paciente?->id;
            abort_unless($pacienteId, 403);

            $data = [
                'paciente_id' => $pacienteId,
                'medico_id' => $user->paciente->medico_id,
                'fecha_hora' => null,
                'estado' => 'pendiente',
                'motivo' => $data['motivo'] ?? null,
                'motivo_detalle' => $data['motivo_detalle'] ?? null,
                'preferencia_fecha_hora' => $data['preferencia_fecha_hora'] ?? null,
            ];

            Cita::create($data);

            session()->flash('success', 'Solicitud enviada correctamente.');
            return redirect()->route('dashboard.paciente', ['tab' => 'cita']);
        }

        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            abort_unless($medicoId, 403);

            // P0: el médico solo crea citas para sus pacientes
            if (!empty($data['paciente_id'])) {
                $paciente = Paciente::find((int) $data['paciente_id']);
                abort_unless($paciente, 404);
                $this->authorize('view', $paciente);
            } else {
                abort(422, 'paciente_id es obligatorio para crear cita como médico.');
            }

            $data['medico_id'] = $medicoId;
            $data['estado'] = $data['estado'] ?? 'aceptada';
        }

        // Admin: tal cual
        Cita::create($data);

        session()->flash('success', 'Cita creada correctamente.');
        return redirect()->route('citas.index');
    }

    public function show(Cita $cita)
    {
        $this->authorize('view', $cita);
        return view('citas.show', ['cita' => $cita]);
    }

    public function edit(Cita $cita)
    {
        $this->authorize('update', $cita);

        $user = Auth::user();

        // P0: quitar fugas globales
        $medicamentos = Medicamento::all();

        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            abort_unless($medicoId, 403);

            $pacientes = Paciente::query()
                ->with('usuarioAcceso')
                ->where('medico_id', $medicoId)
                ->orderByDesc('id')
                ->get();

            return view('citas.edit', [
                'cita' => $cita,
                'medico' => $user->medico,
                'pacientes' => $pacientes,
                'medicamentos' => $medicamentos,
            ]);
        }

        if ($user->es_paciente) {
            $medicoAsignado = $user->paciente?->medico;

            return view('citas.edit', [
                'cita' => $cita,
                'paciente' => $user->paciente,
                'medicos' => $medicoAsignado ? collect([$medicoAsignado->load('user')]) : collect([]),
                'medicamentos' => $medicamentos,
            ]);
        }

        // Admin
        return view('citas.edit', [
            'cita' => $cita,
            'pacientes' => Paciente::with('usuarioAcceso')->orderByDesc('id')->get(),
            'medicos' => Medico::with('user')->get(),
            'medicamentos' => $medicamentos,
        ]);
    }

    public function update(UpdateCitaRequest $request, Cita $cita)
    {
        $this->authorize('update', $cita);

        $data = $request->validated();

        // P0: evitar reasignaciones por update (medico/paciente no deben cambiar medico_id/paciente_id)
        if (Auth::user()->es_medico || Auth::user()->es_paciente) {
            unset($data['medico_id'], $data['paciente_id']);
        }

        $cita->fill($data);
        $cita->save();

        session()->flash('success', 'Cita modificada correctamente.');
        return redirect()->route('citas.index');
    }

    public function destroy(Cita $cita)
    {
        $this->authorize('delete', $cita);

        if ($cita->delete()) {
            session()->flash('success', 'Cita borrada correctamente.');
        } else {
            session()->flash('warning', 'La cita no pudo borrarse.');
        }

        return redirect()->route('citas.index');
    }

    public function attach_medicamento(Request $request, Cita $cita)
    {
        $this->authorize('update', $cita);

        $this->validateWithBag('attach', $request, [
            'medicamento_id' => 'required|exists:medicamentos,id',
            'inicio' => 'required|date',
            'fin' => 'required|date|after:inicio',
            'comentarios' => 'nullable|string',
            'tomas_dia' => 'required|numeric|min:0',
        ]);

        $cita->medicamentos()->attach($request->medicamento_id, [
            'inicio' => $request->inicio,
            'fin' => $request->fin,
            'comentarios' => $request->comentarios,
            'tomas_dia' => $request->tomas_dia,
        ]);

        return redirect()->route('citas.edit', $cita->id);
    }

    public function detach_medicamento(Cita $cita, Medicamento $medicamento)
    {
        $this->authorize('update', $cita);

        $cita->medicamentos()->detach($medicamento->id);
        return redirect()->route('citas.edit', $cita->id);
    }

    public function aceptar(Request $request, Cita $cita)
    {
        $this->authorize('update', $cita);

        $user = Auth::user();
        abort_unless($user->es_medico, 403);

        // Refuerzo: no aceptar citas de otro médico
        if ($cita->medico_id !== null && (int) $cita->medico_id !== (int) $user->medico->id) {
            abort(403);
        }

        $request->validate([
            'fecha_hora' => ['required', 'date'],
            'comentario_medico' => ['nullable', 'string', 'max:2000'],
        ]);

        $cita->update([
            'estado' => 'aceptada',
            'fecha_hora' => $request->fecha_hora,
            'comentario_medico' => $request->comentario_medico,
            'respondida_at' => now(),
            'medico_id' => $user->medico->id,
        ]);

        session()->flash('success', 'Solicitud aceptada.');
        return redirect()->route('citas.index');
    }

    public function rechazar(Request $request, Cita $cita)
    {
        $this->authorize('update', $cita);

        $user = Auth::user();
        abort_unless($user->es_medico, 403);

        // Refuerzo: no rechazar citas de otro médico
        if ($cita->medico_id !== null && (int) $cita->medico_id !== (int) $user->medico->id) {
            abort(403);
        }

        $request->validate([
            'comentario_medico' => ['nullable', 'string', 'max:2000'],
        ]);

        $cita->update([
            'estado' => 'rechazada',
            'comentario_medico' => $request->comentario_medico,
            'respondida_at' => now(),
            'medico_id' => $user->medico->id,
        ]);

        session()->flash('success', 'Solicitud rechazada.');
        return redirect()->route('citas.index');
    }

    public function asignarMedico(Request $request, Paciente $paciente)
    {
        $user = $request->user();

        // P0: esto NO puede ser médico. Solo admin.
        abort_unless($user && $user->es_administrador, 403);

        $request->validate([
            'medico_id' => ['required', 'exists:medicos,id'],
        ]);

        $paciente->update([
            'medico_id' => $request->medico_id,
        ]);

        return back()->with('success', 'Médico asignado correctamente.');
    }
}
