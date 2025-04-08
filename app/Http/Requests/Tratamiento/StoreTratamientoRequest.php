<?php

namespace App\Http\Requests\Tratamiento;

use App\Models\Tratamiento;
use Illuminate\Foundation\Http\FormRequest;

class StoreTratamientoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Tratamiento::class);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tratamiento' => 'required|string',
            'fecha_asignacion' => 'required|date',
            'descripcion' => 'text',
            'duracion_trat' => 'required|numeric', 
            'medico_id' => 'required|exists:medicos,id',
        ];
    }
}
