<?php

namespace App\Http\Requests\Paciente;

use App\Models\Paciente;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('paciente')) ?? false;
    }

    public function rules(): array
    {
        return [
            // PACIENTE
            'fecha_nacimiento' => ['required', 'date'],
            'sexo'             => ['required', 'in:M,F'],
            'peso'             => ['nullable', 'numeric', 'min:1', 'max:500'],
            'altura'           => ['nullable', 'numeric', 'min:30', 'max:300'],

            // ASIGNACIÓN
            'medico_id'        => ['required', 'exists:medicos,id'],
        ];
    }
}
