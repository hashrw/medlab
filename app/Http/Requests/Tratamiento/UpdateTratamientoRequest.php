<?php

namespace App\Http\Requests\Tratamiento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateTratamientoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $tratamiento = $this->route('tratamiento'); // Binding {tratamiento} de la ruta
        return $tratamiento
            ? $this->user()->can('update', $tratamiento)
            : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->user()->es_paciente) {
            return [
                'tratamiento' => 'required|string',
                'fecha_asignacion' => 'required|date',
                'descripcion' => 'nullable|string|max:2000',
                'duracion_trat' => 'required|numeric',
                // 'medico_id' eliminado porque se deduce en backend
            ];
        }

        return [
            'tratamiento' => 'required|string',
            'fecha_asignacion' => 'required|date',
            'descripcion' => 'nullable|string|max:2000',
            'duracion_trat' => 'numeric',
            // 'medico_id' eliminado también para médicos/admins
        ];

    }
}
