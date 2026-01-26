<?php

namespace App\Http\Requests\Medico;

use App\Models\Medico;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMedicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('medico')) ?? false;
    }

    public function rules(): array
    {
        $medico = $this->route('medico');
        $userId = $medico->user_id;

        return [
            // USER
            'name'        => 'required|string|max:255',
            'apellidos'   => 'required|string|max:255',
            'email'       => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'telefono'    => 'nullable|string|max:20',
            'password'    => 'nullable|string|confirmed|min:8',

            // MÉDICO
            'residente'        => 'required|boolean',
            'especialidad_id'  => 'required|exists:especialidads,id',
        ];
    }
}
