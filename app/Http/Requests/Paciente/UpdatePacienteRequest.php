<?php

namespace App\Http\Requests\Paciente;

use App\Models\Paciente;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePacienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $paciente = Paciente::find($this->route('paciente'))->first();
        return $paciente && $this->user()->can('update', $paciente);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'apellidos' => 'string|max:255',
            'telefono' => 'numeric|max:255',
            'nuhsa' => 'string|max:255',
            'edad' => 'numeric|max:255',
            'peso' => 'numeric|max:255',
            'altura' => 'numeric|max:255',
            'sexo' => 'string|max:255',
        ];
    }
}
