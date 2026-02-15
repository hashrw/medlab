<?php

namespace App\Http\Requests\Paciente;

use Illuminate\Foundation\Http\FormRequest;

class StorePacienteSintomaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $paciente = $this->route('paciente');
        return $paciente && $this->user()->can('update', $paciente);
    }

    public function rules(): array
    {
        return [
            'sintomas' => ['required', 'array', 'min:1'],
            'sintomas.*' => ['integer', 'distinct', 'exists:sintomas,id'],
            'fecha_observacion' => ['nullable', 'date'],
            'fuente' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'sintomas.required' => 'Debe seleccionarse al menos un síntoma.',
            'sintomas.min' => 'Debe seleccionarse al menos un síntoma.',
        ];
    }
}
