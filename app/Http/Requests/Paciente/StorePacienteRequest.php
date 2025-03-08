<?php

namespace App\Http\Requests\Paciente;

use App\Models\Paciente;
use Illuminate\Foundation\Http\FormRequest;

class StorePacienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Paciente::class);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'numeric|max:255',
            'password' => 'required|alpha_num|confirmed|min:8',
            'nuhsa' => 'required|string|max:255',
            'edad' => 'required|numeric|max:255',
            'peso' => 'required|numeric|max:255',
            'altura' => 'required|numeric|max:255',
            'sexo' => 'required|string|max:255',
        ];
    }
}
