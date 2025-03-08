<?php

namespace App\Http\Controllers;

use App\Http\Requests\Paciente\StorePacienteRequest;
use App\Http\Requests\Paciente\UpdatePacienteRequest;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Paciente::class);
        $pacientes = Paciente::paginate(25);
        return view('/pacientes/index', ['pacientes' => $pacientes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Paciente::class);
        return view('pacientes/create');
    }

    private function createUser(Request $request)
    {
        $user = new User($request->validated());
        $user->save();
        return $user;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePacienteRequest $request)
    {
        $this->authorize('create', Paciente::class);
        // TODO: La creación de user y paciente debería hacerse transaccionalmente. ¿Demasiado avanzado?
        $user = $this->createUser($request);
        $paciente = new Paciente($request->validated());
        $paciente->user_id = $user->id;
        $paciente->save();
        session()->flash('success', 'Paciente creado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('pacientes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente)
    {
        $this->authorize('view', $paciente);
        return view('pacientes/show', ['paciente' => $paciente]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paciente $paciente)
    {
        $this->authorize('update', $paciente);
        return view('pacientes/edit', ['paciente' => $paciente]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePacienteRequest $request, Paciente $paciente)
    {
        // TODO: La edición de user y paciente debería hacerse transaccionalmente. ¿Demasiado avanzado?
        $user = $paciente->user;
        $user->fill($request->validated());
        $user->save();
        $paciente->fill($request->validated());
        $paciente->save();
        session()->flash('success', 'Paciente modificado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        if($request->user()->es_administrador)
            return redirect()->route('pacientes.index');
        return redirect()->route('citas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {
        $this->authorize('delete', $paciente);
        if($paciente->delete() && $paciente->user->delete())
            session()->flash('success', 'Paciente borrado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        else
            session()->flash('warning', 'El paciente no pudo borrarse. Es probable que se deba a que tenga asociada información como citas que dependen de él.');
        return redirect()->route('pacientes.index');
    }
}