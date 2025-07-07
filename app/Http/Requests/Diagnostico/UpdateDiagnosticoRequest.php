<?php

namespace App\Http\Requests\Diagnostico;

use App\Models\Diagnostico;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        if ($this->user()->es_medico)
            return [
                'medico_id' => ['required', 'exists:medicos,id', Rule::in($this->user()->medico->id)]
            ];

        return [

            'origen' => 'nullable|string',
            'tipo_enfermedad' => 'nullable|string|max:255',
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
