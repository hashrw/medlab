<?php

namespace App\Http\Controllers;

use App\Http\Requests\Medico\StoreMedicoRequest;
use App\Http\Requests\Medico\UpdateMedicoRequest;
use App\Models\Especialidad;
use App\Models\Medico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    private function createUser(Request $request)
    {
        $user = new User($request->validated());
        $user->save();
        return $user;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicoRequest $request)
    {
        $this->authorize('create', Medico::class);
        // TODO: La creación de user y médico debería hacerse transaccionalmente. ¿Demasiado avanzado?
        $user = $this->createUser($request);
        $medico = new Medico($request->validated());
        $medico->user_id = $user->id;
        $medico->save();
        session()->flash('success', 'Médico creado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('medicos.index');
    }

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
    public function update(UpdateMedicoRequest $request, Medico $medico)
    {
        // TODO: La edición de user y médico debería hacerse transaccionalmente. ¿Demasiado avanzado?
        $user = $medico->user;
        $user->fill($request->validated());
        $user->save();
        $medico->fill($request->validated());
        $medico->save();
        session()->flash('success', 'Médico modificado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        if($request->user()->es_administrador)
            return redirect()->route('medicos.index');
        return redirect()->route('citas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medico $medico)
    {
        $this->authorize('delete', $medico);
        if($medico->delete() && $medico->user->delete())
            session()->flash('success', 'Médico borrado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        else
            session()->flash('warning', 'El médico no pudo borrarse. Es probable que se deba a que tenga asociada información como citas que dependen de él.');
        return redirect()->route('medicos.index');
    }
}
