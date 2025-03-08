<?php

namespace App\Http\Requests\Medico;

use App\Models\Medico;
use Illuminate\Foundation\Http\FormRequest;

class StoreMedicoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Medico::class);
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
            'password' => 'required|string|confirmed|min:8',
            'residente' => 'required|boolean',
            'especialidad_id' => 'required|exists:especialidads,id',
        ];
    }

}
