<?php

namespace App\Http\Controllers;

use App\Http\Requests\Prueba\StorePruebaRequest;
use App\Http\Requests\Prueba\UpdatePruebaRequest;
use App\Models\Prueba;
use App\Models\TipoPrueba;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PruebaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Prueba::class);

        $user = Auth::user();

        $query = Prueba::query()->with(['tipo_prueba', 'paciente.usuarioAcceso']);

        // P0: scoping por rol
        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            abort_unless($medicoId, 403);

            $query->whereHas('paciente', function ($q) use ($medicoId) {
                $q->where('medico_id', $medicoId);
            });
        } elseif ($user->es_paciente) {
            $pacienteId = $user->paciente?->id;
            abort_unless($pacienteId, 403);

            $query->where('paciente_id', $pacienteId);
        }

        // FILTRO POR TIPO
        if ($request->filled('tipo')) {
            $query->where('tipo_prueba_id', $request->tipo);
        }

        //$pruebas = $query->orderBy('fecha', 'desc')->paginate(10)->withQueryString();
        $pruebas = $query->orderBy('fecha', 'desc')->paginate(10)->appends($request->query());

        $tipos = TipoPrueba::all();

        return view('pruebas.index', compact('pruebas', 'tipos'));
    }

    public function create()
    {
        $this->authorize('create', Prueba::class);

        $user = Auth::user();
        $paciente = request()->route('paciente');

        if ($paciente) {
            if (!($paciente instanceof Paciente)) {
                $paciente = Paciente::findOrFail((int) $paciente);
            }

            // nested: devolver $paciente explícito (blade lo usa)
            return view('pruebas.create', [
                'paciente' => $paciente,
                'pacientes' => collect([$paciente]), // lo puedes seguir usando si lo necesitas
                'tipos' => TipoPrueba::all(),
            ]);
        }

        // no-nested (si lo usas)
        if ($user->es_medico) {
            $medicoId = $user->medico?->id;
            abort_unless($medicoId, 403);

            $pacientes = Paciente::where('medico_id', $medicoId)->get();

            return view('pruebas.create', [
                'pacientes' => $pacientes,
                'tipos' => TipoPrueba::all(),
            ]);
        }

        abort(403);
    }


    public function store(StorePruebaRequest $request)
    {
        $this->authorize('create', Prueba::class);

        $data = $request->validated();

        $pacienteFromRoute = $request->route('paciente'); // Paciente|null o string/int
        $paciente = null;

        if ($pacienteFromRoute) {
            if (!($pacienteFromRoute instanceof Paciente)) {
                $pacienteFromRoute = Paciente::findOrFail((int) $pacienteFromRoute);
            }

            $paciente = $pacienteFromRoute;
            $data['paciente_id'] = (int) $paciente->id;
        } else {
            $pacienteIdFromBody = isset($data['paciente_id']) ? (int) $data['paciente_id'] : null;
            $paciente = $pacienteIdFromBody ? Paciente::find($pacienteIdFromBody) : null;
        }

        // Seguridad / ownership
        if ($request->user()->es_medico) {
            if (!$paciente) {
                abort(422, 'Paciente no informado para registrar la prueba.');
            }

            $this->authorize('view', $paciente);
            $data['paciente_id'] = (int) $paciente->id;
        }

        // Si el creador es paciente, forzar su propio paciente_id
        if ($request->user()->es_paciente) {
            $pacienteUser = $request->user()->paciente;
            abort_unless($pacienteUser, 403);

            $data['paciente_id'] = (int) $pacienteUser->id;
            $paciente = $pacienteUser;
        }

        $prueba = Prueba::create($data);

        session()->flash('success', 'Prueba creada correctamente.');

        // Flujo serio: volver a ficha del paciente si existe
        if ($paciente) {
            return redirect()->route('pacientes.show', (int) $paciente->id);
        }

        return redirect()->route('pruebas.index');
    }

    public function show(Prueba $prueba)
    {
        $this->authorize('view', $prueba);

        $prueba->loadMissing(['tipo_prueba', 'paciente.usuarioAcceso']);

        return view('pruebas/show', ['prueba' => $prueba]);
    }

    public function edit(Prueba $prueba)
    {
        $this->authorize('update', $prueba);

        $prueba->loadMissing(['tipo_prueba', 'paciente.usuarioAcceso']);

        return view('pruebas/edit', ['prueba' => $prueba]);
    }

    public function update(UpdatePruebaRequest $request, Prueba $prueba)
    {
        $this->authorize('update', $prueba);

        $data = $request->validated();

        // P0: evitar que se reasigne prueba a otro paciente por update
        unset($data['paciente_id']);

        $prueba->fill($data);
        $prueba->save();

        session()->flash('success', 'Registro modificado correctamente.');
        return redirect()->route('pruebas.index');
    }

    public function destroy(Prueba $prueba)
    {
        $this->authorize('delete', $prueba);

        if ($prueba->delete()) {
            session()->flash('success', 'Registro borrado correctamente.');
        } else {
            session()->flash('warning', 'No pudo borrarse el registro.');
        }

        return redirect()->route('pruebas.index');
    }
}
