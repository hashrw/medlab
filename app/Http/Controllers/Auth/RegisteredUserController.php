<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Especialidad;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Rules\Nuhsa;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Selector (si lo mantienes).
     * Ojo: luego lo cerraremos (admin/backoffice). Por ahora solo UI.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function create_medico(): View
    {
        $especialidads = Especialidad::orderBy('nombre')->get();

        return view('auth.register-medico', [
            'especialidads' => $especialidads,
        ]);
    }

    public function create_paciente(): View
    {
        $medicos = Medico::with('user')->orderBy('id')->get();

        return view('auth.register-paciente', [
            'medicos' => $medicos,
        ]);
    }

    private function reglasBase(Request $request): array
    {
        return [
            'tipo_usuario_id' => ['required', 'in:1,2'],
            'name' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }

    private function reglasMedico(): array
    {
        return [
            'especialidad_id' => ['required', 'exists:especialidads,id'],
            'residente' => ['required', 'boolean'],
        ];
    }

    private function reglasPaciente(): array
    {
        return [
            'nuhsa' => ['required', 'string', 'size:12', new Nuhsa],
            'fecha_nacimiento' => ['required', 'date'],
            'sexo' => ['required', 'in:M,F,O'],
            'medico_id' => ['nullable', 'exists:medicos,id'],
        ];
    }

    private function reglas(Request $request): array
    {
        $base = $this->reglasBase($request);

        $tipo = (int) $request->input('tipo_usuario_id');
        if ($tipo === 1) {
            return array_merge($base, $this->reglasMedico());
        }

        if ($tipo === 2) {
            return array_merge($base, $this->reglasPaciente());
        }

        // Nunca debería llegar por el in:1,2, pero lo dejamos claro.
        throw ValidationException::withMessages([
            'tipo_usuario_id' => 'Tipo de usuario no válido.',
        ]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->reglas($request));

        $tipo = (int) $validated['tipo_usuario_id'];

        $user = DB::transaction(function () use ($validated, $tipo) {

            if ($tipo === 2) {
                // PACIENTE: primero ficha clínica, luego user vinculando paciente_id.
                $paciente = new Paciente();
                $paciente->nuhsa = $validated['nuhsa'];
                $paciente->fecha_nacimiento = $validated['fecha_nacimiento'];
                $paciente->sexo = $validated['sexo'];

                // medico_id existe en tu DB por lo que has mostrado (belongsTo). Si es nullable, perfecto.
                if (!empty($validated['medico_id'])) {
                    $paciente->medico_id = (int) $validated['medico_id'];
                }

                $paciente->save();

                $user = User::create([
                    'name' => $validated['name'],
                    'apellidos' => $validated['apellidos'],
                    'telefono' => $validated['telefono'] ?? null,
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'tipo_usuario_id' => 2,
                    'paciente_id' => $paciente->id,
                ]);

                return $user->fresh();
            }

            // MEDICO: primero user, luego medico con user_id.
            $user = User::create([
                'name' => $validated['name'],
                'apellidos' => $validated['apellidos'],
                'telefono' => $validated['telefono'] ?? null,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'tipo_usuario_id' => 1,
            ]);

            Medico::create([
                'user_id' => $user->id,
                'especialidad_id' => (int) $validated['especialidad_id'],
                'residente' => (bool) $validated['residente'],
            ]);

            return $user->fresh();
        });

        event(new Registered($user));
        Auth::login($user);

        // Redirección coherente por rol (si prefieres HOME, cámbialo).
        if ($user->es_medico) {
            return redirect()->route('dashboard.medico');
        }

        if ($user->es_paciente) {
            return redirect()->route('dashboard.paciente');
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
