<?php

namespace App\Http\Requests\Admin;

use App\Models\Paciente;
use App\Rules\Nuhsa;
use Illuminate\Foundation\Http\FormRequest;

class StorePacienteBackofficeRequest extends FormRequest
{
    public function authorize(): bool
    {
        //autorización vía Policies
        //return $this->user()?->can('create', Paciente::class) ?? false;

        //Autorizacion por rol
        return auth()->check() && auth()->user()->es_administrador;
    }

    protected function prepareForValidation(): void
    {
        $nuhsa = $this->input('nuhsa');

        if ($nuhsa !== null) {
            $nuhsa = strtoupper(trim((string) $nuhsa));
            $nuhsa = preg_replace('/[\s\-]/', '', $nuhsa);

            // Si se meten solo 10 dígitos, añadimos AN
            if (preg_match('/^\d{10}$/', $nuhsa)) {
                $nuhsa = 'AN' . $nuhsa;
            }

            $this->merge(['nuhsa' => $nuhsa]);
        }
    }

    public function rules(): array
    {
        return [
            // DATOS DE ACCESO (Usuario)
            'name' => ['required', 'string', 'max:255'],
            'apellidos' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // DATOS CLÍNICOS de Paciente
            'nuhsa' => ['required', 'string', 'size:12', 'unique:pacientes,nuhsa', new Nuhsa],
            'fecha_nacimiento' => ['required', 'date'],
            'sexo' => ['required', 'in:M,F'],
            'peso' => ['nullable', 'numeric', 'min:1', 'max:500'],
            'altura' => ['nullable', 'numeric', 'min:30', 'max:300'],

            // ASIGNACIÓN
            'medico_id' => ['required', 'exists:medicos,id'],
        ];
    }
}
