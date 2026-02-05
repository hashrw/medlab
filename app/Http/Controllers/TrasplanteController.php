<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trasplante;
use App\Models\Paciente;
use App\Http\Requests\Trasplante\StoreTrasplanteRequest;
use App\Http\Requests\Trasplante\UpdateTrasplanteRequest;
use Illuminate\Support\Facades\Auth;

class TrasplanteController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Trasplante::class);

        $user = Auth::user();

        $q = Trasplante::query()->with('paciente.usuarioAcceso');

        // P0: scoping
        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            if (!$medicoId) {
                abort(403);
            }

            $q->whereHas('paciente', function ($sub) use ($medicoId) {
                $sub->where('medico_id', $medicoId);
            });
        } elseif ($user->es_paciente) {
            $pacienteId = $user->paciente?->id;
            if (!$pacienteId) {
                abort(403);
            }

            $q->where('paciente_id', $pacienteId);
        }

        if ($request->filled('tipo')) {
            $q->where('tipo_trasplante', $request->tipo);
        }

        if ($request->filled('hla')) {
            $q->where('identidad_hla', $request->hla);
        }

        if ($request->filled('serologia')) {
            $q->where(function ($w) use ($request) {
                $w->where('seropositividad_donante', $request->serologia)
                    ->orWhere('seropositividad_receptor', $request->serologia);
            });
        }

        if ($request->filled('year')) {
            $q->whereYear('fecha_trasplante', $request->year);
        }

        // Correcto: mantener querystring sin withQueryString()
        $trasplantes = $q->paginate(12)->appends($request->query());

        return view('trasplantes.index', ['trasplantes' => $trasplantes]);
    }

    public function create()
    {
        $this->authorize('create', Trasplante::class);

        $user = Auth::user();

        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            if (!$medicoId) {
                abort(403);
            }

            $pacientes = Paciente::query()
                ->with('usuarioAcceso')
                ->where('medico_id', $medicoId)
                ->orderByDesc('id')
                ->get();

            return view('trasplantes.create', ['pacientes' => $pacientes]);
        }

        if ($user->es_paciente) {
            // P0: asegurar consistencia en la vista (usuarioAcceso puede usarse en el select)
            $paciente = $user->paciente?->loadMissing('usuarioAcceso');
            return view('trasplantes.create', ['pacientes' => $paciente ? collect([$paciente]) : collect([])]);
        }

        $pacientes = Paciente::with('usuarioAcceso')->orderByDesc('id')->get();
        return view('trasplantes.create', ['pacientes' => $pacientes]);
    }

    public function store(StoreTrasplanteRequest $request)
    {
        $this->authorize('create', Trasplante::class);

        $data = $request->validated();
        $user = Auth::user();

        if ($user->es_medico && !empty($data['paciente_id'])) {
            $paciente = Paciente::find((int) $data['paciente_id']);
            if (!$paciente) {
                abort(404);
            }
            $this->authorize('view', $paciente);
        }

        if ($user->es_paciente && $user->paciente?->id) {
            $data['paciente_id'] = (int) $user->paciente->id;
        }

        Trasplante::create($data);

        session()->flash('success', 'Registro creado correctamente.');
        return redirect()->route('trasplantes.index');
    }

    public function show(Trasplante $trasplante)
    {
        $this->authorize('view', $trasplante);

        $trasplante->loadMissing('paciente.usuarioAcceso');

        return view('trasplantes.show', ['trasplante' => $trasplante]);
    }

    public function edit(Trasplante $trasplante)
    {
        $this->authorize('update', $trasplante);

        $user = Auth::user();

        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            if (!$medicoId) {
                abort(403);
            }

            $pacientes = Paciente::query()
                ->with('usuarioAcceso')
                ->where('medico_id', $medicoId)
                ->orderBy('nuhsa')
                ->get();
        } elseif ($user->es_paciente) {
            // P0: consistencia
            $paciente = $user->paciente?->loadMissing('usuarioAcceso');
            $pacientes = $paciente ? collect([$paciente]) : collect([]);
        } else {
            $pacientes = Paciente::with('usuarioAcceso')->orderBy('nuhsa')->get();
        }

        return view('trasplantes.edit', [
            'trasplante' => $trasplante,
            'pacientes' => $pacientes,
        ]);
    }

    public function update(UpdateTrasplanteRequest $request, Trasplante $trasplante)
    {
        $this->authorize('update', $trasplante);

        $data = $request->validated();

        if (Auth::user()->es_medico || Auth::user()->es_paciente) {
            unset($data['paciente_id']);
        }

        $trasplante->fill($data);
        $trasplante->save();

        session()->flash('success', 'Registro modificado correctamente.');
        return redirect()->route('trasplantes.index');
    }

    public function destroy(Trasplante $trasplante)
    {
        $this->authorize('delete', $trasplante);

        if ($trasplante->delete()) {
            session()->flash('success', 'Registro borrado correctamente.');
        } else {
            session()->flash('warning', 'No pudo borrarse el registro.');
        }

        return redirect()->route('trasplantes.index');
    }
}
