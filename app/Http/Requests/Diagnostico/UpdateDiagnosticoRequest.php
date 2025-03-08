<?php

namespace App\Http\Requests\Diagnostico;

use App\Models\Diagnostico;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDiagnosticoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $diagnostico = Diagnostico::find($this->route('diagnostico'))->first();
        return $diagnostico && $this->user()->can('update', $diagnostico);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo_enfermedad' => 'string|max:255',
            'estado_enfermedad' => 'string|email|max:255|unique:users',
            'comienzo_cronica' => 'string|max:255',
            'escala_karnofsky' => 'date',
            'estado_injerto' => 'boolean',
            'tipo_infeccion' => 'numeric',
            'f_hospitalizacion' => 'date',
            'f_electromiografia' => 'date',
            'f_eval_injerto' => 'date',
            'f_medulograma' => 'date',
            'f_espirometria' => 'date',
            'f_esplenectomia' => 'date',
            'hipoalbuminemia' => 'string|max:255',
            'observaciones' => 'string',
    
        ];
    }
}
