<?php

namespace App\Http\Controllers;

use App\Http\Requests\Medico\StoreMedicoRequest;
use App\Http\Requests\Medico\UpdateMedicoRequest;
use App\Models\Especialidad;
use App\Models\Medico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Cita;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Medico::class);
        $medicos = Medico::paginate(25);
        return view('/medicos/index', ['medicos' => $medicos]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $this->authorize('create', Medico::class);
        $especialidads = Especialidad::all();
        return view('medicos/create', ['especialidads' => $especialidads]);
    }

    private function createUser(StoreMedicoRequest $request): User
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'apellidos' => $request->apellidos ?? null,
            'telefono' => $request->telefono ?? null,
            'password' => Hash::make($request->password),
            'tipo_usuario_id' => 1, // MÉDICO
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show(Medico $medico)
    {
        $this->authorize('view', $medico);
        $especialidads = Especialidad::all();
        return view('medicos/show', ['medico' => $medico, 'especialidads' => $especialidads]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medico $medico)
    {
        $this->authorize('update', $medico);
        $especialidads = Especialidad::all();
        return view('medicos/edit', ['medico' => $medico, 'especialidads' => $especialidads]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function store(StoreMedicoRequest $request)
    {
        $this->authorize('create', Medico::class);

        DB::transaction(function () use ($request) {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'apellidos' => $request->apellidos,
                'telefono' => $request->telefono,
                'password' => Hash::make($request->password),
                'tipo_usuario_id' => 1, // MÉDICO
            ]);

            Medico::create([
                'user_id' => $user->id,
                'residente' => $request->residente,
                'especialidad_id' => $request->especialidad_id,
            ]);
        });

        session()->flash('success', 'Médico creado correctamente.');

        return redirect()->route('medicos.index');
    }

    public function update(UpdateMedicoRequest $request, Medico $medico)
    {
        $this->authorize('update', $medico);

        DB::transaction(function () use ($request, $medico) {

            // MÉDICO
            $medico->update([
                'residente' => $request->residente,
                'especialidad_id' => $request->especialidad_id,
            ]);

            // USER
            $user = $medico->user;

            $user->name = $request->name;
            $user->apellidos = $request->apellidos ?? $user->apellidos;
            $user->telefono = $request->telefono ?? $user->telefono;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
        });

        session()->flash('success', 'Médico modificado correctamente.');

        return $request->user()->es_administrador
            ? redirect()->route('medicos.index')
            : redirect()->route('citas.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Medico $medico)
    {
        $this->authorize('delete', $medico);
        if ($medico->delete() && $medico->user->delete())
            session()->flash('success', 'Médico borrado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        else
            session()->flash('warning', 'El médico no pudo borrarse. Es probable que se deba a que tenga asociada información como citas que dependen de él.');
        return redirect()->route('medicos.index');

        /*DB::transaction(function () use ($medico) {
    $medico->delete();
    $medico->user->delete();});*/

    }

    /*public function citas_pendientes_count()
    {
        $medicoId = Auth::user()->medico->id;

        $citasPendientes = Cita::query()->where('medico_id', $medicoId)->where('estado', 'pendiente')->count();
        $ultimasPendientes = Cita::query()->with('paciente.user', 'paciente.usuarioAcceso')->where('medico_id', $medicoId)->where('estado', 'pendiente')->orderByDesc('created_at')->limit(5)->get();

        return view('dashboard.medico', [
           // 'stats' => $stats,
           // 'ultimos' => $ultimos,
            'citasPendientesCount' => $citasPendientes,
            'citasPendientesTop' => $ultimasPendientes,
        ]);

    }*/
}
