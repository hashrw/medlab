<?php

namespace App\Http\Controllers;

use App\Http\Requests\Paciente\StorePacienteRequest;
use App\Http\Requests\Paciente\UpdatePacienteRequest;
use App\Http\Requests\Paciente\StorePacienteSintomaRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Paciente;
use App\Models\Sintoma;
use Illuminate\Http\Request;
use App\Models\Organo;
use App\Http\Requests\Paciente\StorePacienteOrganoScoreRequest;


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

            // Orden por fecha (ajusta el nombre del campo si no es "fecha")
            'trasplantes' => fn($q) => $q->orderByDesc('fecha_trasplante'),

            // Tratamientos
            'tratamientos',
            'tratamientos.lineasTratamiento',
            'tratamientos.diagnostico',

            // Síntomas (tu comentario dice "filtra activo=true": eso NO lo hace el load por sí solo,
            // depende de cómo esté definida la relación en el modelo)
            'sintomas',

            // Pruebas: ordenar por fecha y cargar tipo_prueba
            'pruebas' => fn($q) => $q->orderByDesc('fecha'),
            'pruebas.tipo_prueba',

            // Órganos
            'organos',

            // Diagnósticos
            'diagnosticos.origen',
            'diagnosticos.regla',
            // OJO: NO cargar 'diagnosticos.enfermedad' si NO existe relación.
            'diagnosticos.sintomas.organo',
        ]);

        /*dd(
            $paciente->trasplantes()
                ->orderByDesc('fecha_trasplante')
                ->pluck('fecha_trasplante')
        );*/

        $tieneSintomasActivos = $paciente->sintomas->isNotEmpty();

        $sintomasCatalogo = Sintoma::query()
            ->with('organo:id,nombre')
            ->orderBy('organo_id')
            ->orderBy('sintoma')
            ->get(['id', 'sintoma', 'manif_clinica', 'organo_id']);

        $organosCatalogo = Organo::query()
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        return view('pacientes.show', compact(
            'paciente',
            'tieneSintomasActivos',
            'sintomasCatalogo',
            'organosCatalogo'
        ));
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


    public function storeSintomas(StorePacienteSintomaRequest $request, Paciente $paciente)
    {
        $this->authorize('update', $paciente);

        $data = $request->validated();

        $fecha = $data['fecha_observacion'] ?? now()->toDateString();
        $fuente = $data['fuente'] ?? 'UI_MEDICO';

        DB::transaction(function () use ($paciente, $data, $fecha, $fuente) {

            foreach ($data['sintomas'] as $sid) {
                DB::table('paciente_sintoma')->updateOrInsert(
                    [
                        'paciente_id' => (int) $paciente->id,
                        'sintoma_id' => (int) $sid,
                    ],
                    [
                        'activo' => true,
                        'fecha_observacion' => $fecha,
                        'fuente' => $fuente,
                        'updated_at' => now(),
                        // si la fila es nueva, se setea created_at; si ya existe, se mantiene
                        'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                    ]
                );
            }
        });

        return redirect()
            ->route('pacientes.show', $paciente->id)
            ->with('success', 'Síntomas registrados correctamente.');
    }

    public function storeOrganoScores(StorePacienteOrganoScoreRequest $request, Paciente $paciente)
    {
        $this->authorize('update', $paciente);

        $data = $request->validated();

        $fecha = $data['fecha_evaluacion'] ?? now()->toDateString();
        $comentario = $data['comentario'] ?? null;

        DB::transaction(function () use ($paciente, $data, $fecha, $comentario) {
            foreach ($data['organos'] as $organoId => $payload) {
                $score = $payload['score_nih'] ?? null;

                // Si viene vacío, no tocamos nada (incremental, sin borrar)
                if ($score === null || $score === '') {
                    continue;
                }

                $paciente->organos()->syncWithoutDetaching([
                    (int) $organoId => [
                        'score_nih' => (int) $score,
                        'fecha_evaluacion' => $fecha,
                        'comentario' => $comentario,
                        // mantenemos los demás campos pivot si existen en tu migración
                        // 'sintomas_asociados' => null,
                    ],
                ]);
            }
        });

        return redirect()
            ->route('pacientes.show', $paciente->id)
            ->with('success', 'Evaluación NIH por órgano guardada correctamente.');
    }


}
