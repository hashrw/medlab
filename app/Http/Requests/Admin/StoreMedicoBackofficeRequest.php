<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicoBackofficeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Backoffice: solo administradores
        return auth()->check() && auth()->user()->es_administrador;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('residente')) {
            $this->merge([
                'residente' => filter_var($this->residente, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            // ===== USER =====
            'name'        => ['required', 'string', 'max:255'],
            'apellidos'   => ['nullable', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'telefono'    => ['nullable', 'string', 'max:20'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],

            // Avatar
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            // ===== MÉDICO =====
            'residente'        => ['required', 'boolean'],
            'especialidad_id'  => ['required', 'exists:especialidads,id'],
        ];
    }
}
