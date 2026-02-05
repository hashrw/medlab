<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Especialidad;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StorePacienteBackofficeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{

    public function index()
    {
        return view('dashboard.admin');
    }

    /**
     * Pantalla “selector” para alta administrativa.
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Formulario de alta de paciente.
     * Necesitamos listar médicos para poder asignar uno (opcional).
     */
    public function createPaciente()
    {
        $medicos = Medico::with('user')->orderBy('id')->get();

        return view('admin.usuarios.create-paciente', [
            'medicos' => $medicos
        ]);
    }

    /**
     * Formulario de alta de médico.
     * Necesitamos especialidades para el select.*/

    public function createMedico()
    {
        $especialidades = Especialidad::orderBy('nombre')->get();

        return view('admin.usuarios.create-medico', [
            'especialidades' => $especialidades
        ]);
    }

    /**
     * Guardar paciente:
     * 1) Validamos
     * 2) Creamos PACIENTE
     * 3) Creamos USER con tipo_usuario_id=2 y paciente_id apuntando al paciente.
     *
     * Importante:
     * - Lo hacemos en transacción para que NO queden datos a medias.
     */
    public function storePaciente(StorePacienteBackofficeRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated) {
                // 1) Crear paciente (ya viene medico_id validado)
                $paciente = new Paciente();
                $paciente->nuhsa = $validated['nuhsa'];
                $paciente->fecha_nacimiento = $validated['fecha_nacimiento'];
                $paciente->sexo = $validated['sexo'];
                $paciente->peso = $validated['peso'] ?? null;
                $paciente->altura = $validated['altura'] ?? null;
                $paciente->medico_id = $validated['medico_id'];
                $paciente->save();

                // 2) Crear usuario de acceso y enlazarlo
                $user = new User();
                $user->name = $validated['name'];
                $user->apellidos = $validated['apellidos'] ?? null;
                $user->telefono = $validated['telefono'] ?? null;
                $user->email = $validated['email'];
                $user->password = Hash::make($validated['password']);

                $user->tipo_usuario_id = 2;           // paciente
                $user->paciente_id = $paciente->id;   // enlace fuerte
                $user->save();
            });

            return redirect()
                ->route('admin.usuarios.createPaciente')
                ->with('success', 'Usuario paciente creado correctamente.');
        } /*catch (\Throwable $e) {
           return back()
               ->withInput()
               ->withErrors(['error' => 'Error al crear usuario: ' . $e->getMessage()]);
       }*/ 
               catch (\Throwable $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }

    /**
     * Guardar médico:
     * 1) Validamos
     * 2) Creamos USER con tipo_usuario_id=1
     * 3) Creamos MEDICO con user_id apuntando al user
     */
    public function storeMedico(Request $request)
    {
        $request->validate([
            'tipo_usuario_id' => ['required', 'in:1'],

            'name' => ['required', 'string', 'max:255'],
            'apellidos' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],

            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'especialidad_id' => ['required', 'exists:especialidads,id'],
            'residente' => ['required', 'in:0,1'],
        ]);

        DB::beginTransaction();

        try {
            // 1) Crear user
            $user = new User();
            $user->name = $request->name;
            $user->apellidos = $request->apellidos;
            $user->telefono = $request->telefono;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->tipo_usuario_id = 1; // médico
            $user->save();

            // 2) Crear medico
            $medico = new Medico();
            $medico->user_id = $user->id;
            $medico->especialidad_id = $request->especialidad_id;
            $medico->residente = (bool) $request->residente;
            $medico->save();

            DB::commit();

            return redirect()
                ->route('admin.usuarios.createMedico')
                ->with('success', 'Médico creado correctamente.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear médico: ' . $e->getMessage()]);
        }
    }
}
