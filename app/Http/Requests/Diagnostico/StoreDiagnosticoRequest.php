<?php

namespace App\Http\Requests\Diagnostico;

use App\Models\Diagnostico;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

        if ($this->user()->es_medico)
            return [
                'medico_id' => ['required', 'exists:medicos,id', Rule::in($this->user()->medico->id)]
            ];
        return [

            'tipo_enfermedad' => 'nullable|string|max:255',
            'estado_enfermedad' => 'nullable|string|max:255',
            'comienzo_cronica' => 'nullable|string|max:255',
            'escala_karnofsky' => 'nullable|numeric',
            'estado_injerto' => 'nullable|string',
            'dias_desde_trasplante' => 'nullable|integer',
            'tipo_infeccion' => 'nullable|string',
            'f_trasplante' => 'nullable|date',
            'f_electromiografia' => 'nullable|date',
            'f_eval_injerto' => 'nullable|date',
            'f_medulograma' => 'nullable|date',
            'f_espirometria' => 'nullable|date',
            'f_esplenectomia' => 'nullable|date',
            'hipoalbuminemia' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'sintomas' => 'nullable|array',
            'sintomas.*.fecha_diagnostico' => 'nullable|date',
            'sintomas.*.score_nih' => 'nullable|integer',
            'estado_id' => 'required|exists:estados,id',
            'comienzo_id' => 'required|exists:comienzos,id',
            'infeccion_id' => 'required|exists:infeccions,id',
        ];
    }
}
