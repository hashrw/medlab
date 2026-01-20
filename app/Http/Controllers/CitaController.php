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
            $q = $user->medico->citas()->getQuery();

            $q->orderByRaw("CASE WHEN estado = 'pendiente' THEN 0 ELSE 1 END")
              ->orderByDesc('created_at');

            if ($request->filled('estado')) {
                $q->where('estado', $request->estado);
            }

            $citas = $q->paginate(25)->withQueryString();
            return view('citas.index', compact('citas'));
        }

        if ($user->es_paciente) {
            $citas = $user->paciente->citas()
                ->orderByDesc('created_at')
                ->paginate(25);

            return view('citas.index', compact('citas'));
        }

        $citas = Cita::orderByDesc('created_at')->paginate(25);
        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        $this->authorize('create', Cita::class);

        $medicos = Medico::all();
        $pacientes = Paciente::all();

        if (Auth::user()->es_medico) {
            return view('citas.create', [
                'medico' => Auth::user()->medico,
                'pacientes' => $pacientes,
            ]);
        }

        if (Auth::user()->es_paciente) {
            return view('citas.create', [
                'paciente' => Auth::user()->paciente,
                'medicos' => $medicos,
            ]);
        }

        return view('citas.create', ['pacientes' => $pacientes, 'medicos' => $medicos]);
    }

    public function store(StoreCitaRequest $request)
    {
        $this->authorize('create', Cita::class);

        $user = Auth::user();
        $data = $request->validated();

        if ($user->es_paciente) {
            // Solicitud: el paciente no fija cita real ni médico
            $data = [
                'paciente_id' => $user->paciente->id,
                'medico_id' => null,
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

        // Médico crea cita “real”
        if ($user->es_medico) {
            $data['estado'] = $data['estado'] ?? 'aceptada';
        }

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

        $medicamentos = Medicamento::all();
        $medicos = Medico::all();
        $pacientes = Paciente::all();

        if (Auth::user()->es_medico) {
            return view('citas.edit', [
                'cita' => $cita,
                'medico' => Auth::user()->medico,
                'pacientes' => $pacientes,
                'medicamentos' => $medicamentos,
            ]);
        }

        if (Auth::user()->es_paciente) {
            return view('citas.edit', [
                'cita' => $cita,
                'paciente' => Auth::user()->paciente,
                'medicos' => $medicos,
                'medicamentos' => $medicamentos,
            ]);
        }

        return view('citas.edit', [
            'cita' => $cita,
            'pacientes' => $pacientes,
            'medicos' => $medicos,
            'medicamentos' => $medicamentos,
        ]);
    }

    public function update(UpdateCitaRequest $request, Cita $cita)
    {
        $this->authorize('update', $cita);

        $cita->fill($request->validated());
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
            // FIX: medicamentos, no medicos
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

        $request->validate([
            'fecha_hora' => ['required', 'date'],
            'comentario_medico' => ['nullable', 'string', 'max:2000'],
        ]);

        $cita->update([
            'estado' => 'aceptada',
            'fecha_hora' => $request->fecha_hora,
            'comentario_medico' => $request->comentario_medico,
            'respondida_at' => now(),
            'medico_id' => Auth::user()->medico->id,
        ]);

        session()->flash('success', 'Solicitud aceptada.');
        return redirect()->route('citas.index');
    }

    public function rechazar(Request $request, Cita $cita)
    {
        $this->authorize('update', $cita);

        $request->validate([
            'comentario_medico' => ['nullable', 'string', 'max:2000'],
        ]);

        $cita->update([
            'estado' => 'rechazada',
            'comentario_medico' => $request->comentario_medico,
            'respondida_at' => now(),
            'medico_id' => Auth::user()->medico->id,
        ]);

        session()->flash('success', 'Solicitud rechazada.');
        return redirect()->route('citas.index');
    }
}
