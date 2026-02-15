<?php

namespace App\Http\Requests\Paciente;

use Illuminate\Foundation\Http\FormRequest;

class StorePacienteOrganoScoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        $paciente = $this->route('paciente');
        return $paciente && $this->user()->can('update', $paciente);
    }

    public function rules(): array
    {
        return [
            'organos' => ['required', 'array', 'min:1'],
            'organos.*.score_nih' => ['nullable', 'integer', 'min:0', 'max:4'],
            'fecha_evaluacion' => ['nullable', 'date'],
            'comentario' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'organos.required' => 'Debe evaluarse al menos un órgano.',
            'organos.min' => 'Debe evaluarse al menos un órgano.',
        ];
    }
}
