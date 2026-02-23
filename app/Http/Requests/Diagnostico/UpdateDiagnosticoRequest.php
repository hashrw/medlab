<?php

namespace App\Http\Requests\Diagnostico;

use App\Models\Diagnostico;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiagnosticoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $diagnostico = $this->route('diagnostico');

        return $diagnostico instanceof Diagnostico
            && $this->user()->can('update', $diagnostico);
    }

    public function rules(): array
    {
        return [
            // Campos clínicos del diagnóstico
            'tipo_enfermedad' => ['nullable', 'string', 'max:255'],
            'estado_injerto' => ['nullable', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string'],
            'grado_eich' => ['nullable', 'string', 'max:255'],

            // Nota: antes se ponía (0-4) en la vista, pero aquí se valida 0-100.
            // Se respeta el criterio del backend (0-100) tal como estaba.
            'escala_karnofsky' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
                Rule::requiredIf(fn () => $this->input('tipo_enfermedad') === 'cronica'),
            ],

            // Síntomas asociados (aunque para inferidos el controller los ignora/bloquea)
            'sintomas' => ['nullable', 'array'],
            'sintomas.*.fecha_diagnostico' => ['nullable', 'date'],
            'sintomas.*.score_nih' => ['nullable', 'integer', 'min:0'],

            // Relaciones opcionales
            'estado_id' => ['nullable', 'exists:estados,id'],
            'comienzo_id' => [
                'nullable',
                'exists:comienzos,id',
                Rule::requiredIf(fn () => $this->input('tipo_enfermedad') === 'cronica'),
            ],
            'infeccion_id' => ['nullable', 'exists:infeccions,id'],

            // En manuales se podría permitir (si el controller lo acepta); en inferidos no.
            'regla_decision_id' => ['nullable', 'exists:regla_decisions,id'],
        ];
    }
}
