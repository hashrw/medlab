<?php

namespace App\Http\Requests\Tratamiento;

use App\Models\Tratamiento;
use Illuminate\Foundation\Http\FormRequest;

class StoreTratamientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Tratamiento::class);
    }

    public function rules(): array
    {
        return [
            'tratamiento' => ['required', 'string', 'max:255'],
            'fecha_asignacion' => ['nullable', 'date'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'paciente_id' => ['required', 'integer', 'exists:pacientes,id'],
        ];
    }
}
