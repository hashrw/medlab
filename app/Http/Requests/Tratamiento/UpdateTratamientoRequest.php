<?php

namespace App\Http\Requests\Tratamiento;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTratamientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $tratamiento = $this->route('tratamiento');
        return $tratamiento ? $this->user()->can('update', $tratamiento) : false;
    }

    public function rules(): array
    {
        // Mismas reglas para paciente/médico porque no vamos a tocar permisos finos aquí.
        return [
            'tratamiento' => ['required', 'string', 'max:255'],
            'fecha_asignacion' => ['nullable', 'date'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
