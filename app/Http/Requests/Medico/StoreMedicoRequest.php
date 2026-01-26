<?php

namespace App\Http\Requests\Medico;

use App\Models\Medico;
use Illuminate\Foundation\Http\FormRequest;

class StoreMedicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Medico::class) ?? false;
    }

    public function rules(): array
    {
        return [
            // USER
            'name'        => 'required|string|max:255',
            'apellidos'   => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email',
            'telefono'    => 'nullable|string|max:20',
            'password'    => 'required|string|confirmed|min:8',

            // MÉDICO
            'residente'        => 'required|boolean',
            'especialidad_id'  => 'required|exists:especialidads,id',
        ];
    }
}
