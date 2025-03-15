<?php

namespace App\Http\Requests\Diagnostico;

use App\Models\Diagnostico;
use Illuminate\Foundation\Http\FormRequest;

class StoreDiagnosticoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Diagnostico::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           
            'medico_id' => 'required|exists:medicos,id',
            'tipo_enfermedad' => 'string|max:255',
            'estado_enfermedad' => 'string|email|max:255|unique:users',
            'comienzo_cronica' => 'string|max:255',
            'escala_karnofsky' => 'date',
            'estado_injerto' => 'boolean',
            'dias_desde_trasplante' => 'nullable|integer',
            'tipo_infeccion' => 'numeric',
            'f_hospitalizacion' => 'date',
            'f_electromiografia' => 'date',
            'f_eval_injerto' => 'date',
            'f_medulograma' => 'date',
            'f_espirometria' => 'date',
            'f_esplenectomia' => 'date',
            'hipoalbuminemia' => 'string|max:255',
            'observaciones' => 'string',
            'sintomas' => 'nullable|array',
            'sintomas.*.fecha_diagnostico' => 'nullable|date',
            'sintomas.*.score_nih' => 'nullable|integer',
        ];
    }
}
