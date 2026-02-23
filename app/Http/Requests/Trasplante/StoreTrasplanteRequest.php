<?php

namespace App\Http\Requests\Trasplante;

use App\Models\Trasplante;
use Illuminate\Foundation\Http\FormRequest;

class StoreTrasplanteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Trasplante::class);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isPaciente = $this->user()?->es_paciente;

        return [
            'tipo_trasplante' => 'nullable|string|max:255',
            'fecha_trasplante' => 'required|date',
            'origen_trasplante' => 'nullable|string|max:255',
            'identidad_hla' => 'nullable|string|max:255',
            'tipo_acondicionamiento' => 'nullable|string|max:255',
            'seropositividad_donante' => 'nullable|string|max:255',
            'seropositividad_receptor' => 'nullable|string|max:255',
            'paciente_id' => ($isPaciente ? 'nullable' : 'required') . '|exists:pacientes,id',
        ];
    }

}
