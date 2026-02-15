<?php

namespace App\Http\Requests\Admin;

use App\Rules\Nuhsa;
use Illuminate\Foundation\Http\FormRequest;

class StorePacienteBackofficeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->es_administrador;
    }

    protected function prepareForValidation(): void
    {
        $nuhsa = $this->input('nuhsa');

        if ($nuhsa !== null) {
            $nuhsa = strtoupper(trim((string) $nuhsa));
            $nuhsa = preg_replace('/[\s\-]/', '', $nuhsa);

            if (preg_match('/^\d{10}$/', $nuhsa)) {
                $nuhsa = 'AN' . $nuhsa;
            }

            $this->merge(['nuhsa' => $nuhsa]);
        }
    }

    public function rules(): array
    {
        return [
            // ===== USER =====
            'name'       => ['required', 'string', 'max:255'],
            'apellidos'  => ['nullable', 'string', 'max:255'],
            'telefono'   => ['nullable', 'string', 'max:50'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],

            // Avatar
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            // ===== PACIENTE =====
            'nuhsa'             => ['required', 'string', 'size:12', 'unique:pacientes,nuhsa', new Nuhsa],
            'fecha_nacimiento'  => ['required', 'date'],
            'sexo'              => ['required', 'in:M,F'],
            'peso'              => ['nullable', 'numeric', 'min:1', 'max:500'],
            'altura'            => ['nullable', 'numeric', 'min:30', 'max:300'],

            // ASIGNACIÓN
            'medico_id' => ['required', 'exists:medicos,id'],
        ];
    }
}
