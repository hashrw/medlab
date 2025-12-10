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
        // Si usas route model binding, esto ya es una instancia de Diagnostico
        $diagnostico = $this->route('diagnostico');

        return $diagnostico instanceof Diagnostico
            && $this->user()->can('update', $diagnostico);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            // Campos clínicos del diagnóstico
            'tipo_enfermedad'   => 'nullable|string|max:255',
            'estado_injerto'    => 'nullable|string|max:255',
            'observaciones'     => 'nullable|string',
            'grado_eich'        => 'nullable|string|max:255',
            'escala_karnofsky'  => 'nullable|string|max:255',

            // Síntomas asociados al diagnóstico (solo si permitimos tocarlos;
            // la restricción real para inferidos la haremos en el controlador)
            'sintomas'                      => 'nullable|array',
            'sintomas.*.fecha_diagnostico'  => 'nullable|date',
            'sintomas.*.score_nih'          => 'nullable|integer|min:0',

            // Relaciones opcionales
            'estado_id'         => 'nullable|exists:estados,id',
            'comienzo_id'       => 'nullable|exists:comienzos,id',
            'infeccion_id'      => 'nullable|exists:infeccions,id',

            // En update podrías aceptar cambiar la regla asociada SOLO en manuales
            // (la lógica la controlaremos luego en el controlador si hace falta)
            'regla_decision_id' => 'nullable|exists:regla_decisions,id',
        ];

        // Si el usuario es médico, validamos que el medico_id coincida con el suyo
        if ($this->user()->es_medico) {
            $rules['medico_id'] = [
                'required',
                'exists:medicos,id',
                Rule::in($this->user()->medico->id),
            ];
        }

        // Importante:
        // - No validamos 'origen' ni 'origen_id' desde el request.
        // - No validamos paciente_id: el paciente NO se cambia en update.
        // - No validamos sintomas.*.origen porque no existe en el pivot.

        return $rules;
    }
}
