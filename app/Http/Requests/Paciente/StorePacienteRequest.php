<?php

namespace App\Http\Requests\Paciente;

use App\Models\Paciente;
use Illuminate\Foundation\Http\FormRequest;

class StorePacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Paciente::class);
    }

    public function rules(): array
    {
        return [
            'nuhsa'             => 'required|string|max:255',
            'fecha_nacimiento'  => 'required|date',
            'peso'              => 'required|numeric|min:1|max:500',
            'altura'            => 'required|numeric|min:30|max:300',
            'sexo'              => 'required|string|in:M,F',
        ];
    }
}
