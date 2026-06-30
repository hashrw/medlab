<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Especialidad;
use App\Models\User;
use App\Http\Requests\Admin\StoreMedicoBackofficeRequest;
use App\Http\Requests\Admin\StorePacienteBackofficeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
     * Listar médicos para asignación.
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
     * Listar especialidades.
     */
    public function createMedico()
    {
        $especialidades = Especialidad::orderBy('nombre')->get();

        return view('admin.usuarios.create-medico', [
            'especialidades' => $especialidades
        ]);
    }

    /**
     * Guardar paciente (Backoffice):
     * - Validación via StorePacienteBackofficeRequest
     * - Transacción
     * - Avatar opcional (se guarda en users.avatar)
     */
    public function storePaciente(StorePacienteBackofficeRequest $request)
    {
        //dd('entra storePaciente');
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($request, $validated) {

                // 1) Crear paciente
                $paciente = new Paciente();
                $paciente->nuhsa = $validated['nuhsa'];
                $paciente->fecha_nacimiento = $validated['fecha_nacimiento'];
                $paciente->sexo = $validated['sexo'];

                // Mantengo esto porque ya lo tenías; si no están en tu Request, no se guardan.
                $paciente->peso = $validated['peso'] ?? null;
                $paciente->altura = $validated['altura'] ?? null;

                $paciente->medico_id = $validated['medico_id'];
                $paciente->save();

                // 2) Crear usuario de acceso
                $user = new User();
                $user->name = $validated['name'];
                $user->apellidos = $validated['apellidos'] ?? null;
                $user->telefono = $validated['telefono'] ?? null;
                $user->email = $validated['email'];
                $user->password = Hash::make($validated['password']);

                $user->tipo_usuario_id = 2;           // paciente
                $user->paciente_id = $paciente->id;   // enlace fuerte


                // 3) Avatar opcional
                if ($request->hasFile('foto')) {
                    $path = $request->file('foto')->store('avatars', 'public');
                    $user->foto = $path; // requiere columna users.avatar nullable
                }

                $user->save();

            });


            return redirect()
                ->route('admin.usuarios.createPaciente')
                ->with('success', 'Paciente creado correctamente.');

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear paciente: ' . $e->getMessage()]);
        }
    }


    public function storeMedico(StoreMedicoBackofficeRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($request, $validated) {

                // 1) Crear user
                $user = new User();
                $user->name = $validated['name'];
                $user->apellidos = $validated['apellidos'] ?? null;
                $user->telefono = $validated['telefono'] ?? null;
                $user->email = $validated['email'];
                $user->password = Hash::make($validated['password']);
                $user->tipo_usuario_id = 1; // médico

                // 2) Avatar opcional
                if ($request->hasFile('foto')) {
                    $path = $request->file('foto')->store('avatars', 'public');
                    $user->foto = $path; // requiere columna users.avatar nullable
                }

                $user->save();

                // 3) Crear medico
                $medico = new Medico();
                $medico->user_id = $user->id;
                $medico->especialidad_id = $validated['especialidad_id'];
                $medico->residente = (bool) $validated['residente'];
                $medico->save();
            });

            return redirect()
                ->route('admin.usuarios.createMedico')
                ->with('success', 'Médico creado correctamente.');

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear médico: ' . $e->getMessage()]);
        }
    }
}
