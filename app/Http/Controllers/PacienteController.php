<?php

namespace App\Http\Controllers;

use App\Http\Requests\Paciente\StorePacienteRequest;
use App\Http\Requests\Paciente\UpdatePacienteRequest;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Paciente::class);

        $query = Paciente::query()->with('usuarioAcceso');

        if ($request->user()?->es_medico) {
            $medicoId = $request->user()?->medico?->id;
            abort_unless($medicoId, 403);

            $query->where('medico_id', $medicoId);
        }

        if ($request->filled('nombre')) {
            $query->whereHas('usuarioAcceso', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nombre . '%');
            });
        }

        if ($request->filled('sexo')) {
            $query->where('sexo', $request->sexo);
        }

        if ($request->filled('edad_min') || $request->filled('edad_max')) {
            $query->where(function ($q) use ($request) {
                if ($request->filled('edad_min')) {
                    $q->whereRaw('TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) >= ?', [$request->edad_min]);
                }
                if ($request->filled('edad_max')) {
                    $q->whereRaw('TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) <= ?', [$request->edad_max]);
                }
            });
        }

        if ($request->filled('imc')) {
            switch ($request->imc) {
                case 'normal':
                    $query->whereRaw('(peso / POWER(altura/100, 2)) < 25');
                    break;

                case 'sobrepeso':
                    $query->whereRaw('(peso / POWER(altura/100, 2)) BETWEEN 25 AND 29.9');
                    break;

                case 'obesidad':
                    $query->whereRaw('(peso / POWER(altura/100, 2)) >= 30');
                    break;
            }
        }

        $pacientes = $query->paginate(15)->appends($request->query());

        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        $this->authorize('create', Paciente::class);

        return view('pacientes.create');
    }

    public function store(StorePacienteRequest $request)
    {
        $this->authorize('create', Paciente::class);

        DB::transaction(function () use ($request) {
            Paciente::create($request->validated());
        });

        session()->flash('success', 'Paciente creado correctamente.');

        return redirect()->route('pacientes.index');
    }

    public function show(Paciente $paciente)
    {
        $this->authorize('view', $paciente);

        $prev = url()->previous();
        $self = route('pacientes.show', $paciente->id);

        if ($prev !== $self) {
            $vieneDeResultado = str_contains($prev, '/diagnosticos/')
                || str_contains($prev, '/tratamientos/');

            if (!$vieneDeResultado) {
                session(['pacientes_back_url' => $prev]);
            }
        }

        $paciente->load([
            'usuarioAcceso',
            'trasplantes',
            'tratamientos',
            'sintomas',
            'pruebas.tipo_prueba',
            'organos',
            'tratamientos.lineasTratamiento',
            'tratamientos.diagnostico',
        ]);

        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        $this->authorize('update', $paciente);

        return view('pacientes.edit', compact('paciente'));
    }

    public function update(UpdatePacienteRequest $request, Paciente $paciente)
    {
        $this->authorize('update', $paciente);

        DB::transaction(function () use ($request, $paciente) {
            $paciente->update($request->validated());
        });

        session()->flash('success', 'Paciente modificado correctamente.');

        return redirect()->route('pacientes.index');
    }

    public function destroy(Paciente $paciente)
    {
        $this->authorize('delete', $paciente);

        $paciente->delete();
        session()->flash('success', 'Paciente borrado correctamente.');

        return redirect()->route('pacientes.index');
    }
}
