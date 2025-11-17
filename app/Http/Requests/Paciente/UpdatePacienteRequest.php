<?php

namespace App\Http\Requests\Paciente;

use App\Models\Paciente;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $paciente = $this->route('paciente');
        return $paciente && $this->user()->can('update', $paciente);
    }

    public function rules(): array
    {
        return [
            'nuhsa'             => 'sometimes|string|max:255',
            'fecha_nacimiento'  => 'sometimes|date',
            'peso'              => 'sometimes|numeric|min:1|max:500',
            'altura'            => 'sometimes|numeric|min:30|max:300',
            'sexo'              => 'sometimes|string|in:M,F',
        ];
    }
}
