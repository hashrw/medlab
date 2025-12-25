<?php

namespace App\Http\Requests\Diagnostico;

use App\Models\Diagnostico;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiagnosticoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Diagnostico::class);
    }

    public function rules(): array
    {
        $rules = [
            // Campos del diagnóstico
            'fecha_diagnostico' => 'nullable|date',
            'tipo_enfermedad' => 'nullable|string|max:255',
            'estado_injerto' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'grado_eich' => 'nullable|string|max:255',
            'escala_karnofsky' => 'nullable|string|max:255',

            // Síntomas asociados (pivot diagnostico_sintoma)
            'sintomas' => 'nullable|array',
            'sintomas.*.fecha_diagnostico' => 'nullable|date',
            'sintomas.*.score_nih' => 'nullable|integer|min:0|max:3',

            // Relaciones opcionales (catálogos)
            'estado_id' => 'nullable|exists:estados,id',
            'comienzo_id' => 'nullable|exists:comienzos,id',
            'infeccion_id' => 'nullable|exists:infeccions,id',

            // Regla (solo si alguna vez creas diagnósticos manuales con regla asociada)
            'regla_decision_id' => 'nullable|exists:regla_decisions,id',

            // No permitimos que el request envíe origen/origen_id (lo fija backend)
            'origen' => ['prohibited'],
            'origen_id' => ['prohibited'],

            // No guardamos medico_id en Diagnostico (y no debe venir del form)
            'medico_id' => ['prohibited'],
        ];

        // Médico: debe seleccionar paciente en el formulario
        if ($this->user()->es_medico) {
            $rules['paciente_id'] = ['required', 'exists:pacientes,id'];
        } else {
            // Paciente u otros roles: no aceptamos paciente_id por request
            // (si en el futuro permites paciente, se toma de $this->user()->paciente_id en controller)
            $rules['paciente_id'] = ['prohibited'];
        }

        return $rules;
    }
}
