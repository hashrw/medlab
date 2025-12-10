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
        $rules = [
            // Campos clínicos del diagnóstico
            'tipo_enfermedad'   => 'nullable|string|max:255',
            'estado_injerto'    => 'nullable|string|max:255',
            'observaciones'     => 'nullable|string',
            'grado_eich'        => 'nullable|string|max:255',
            'escala_karnofsky'  => 'nullable|string|max:255',

            // Síntomas asociados al diagnóstico (pivot diagnostico_sintoma)
            'sintomas'                      => 'nullable|array',
            'sintomas.*.fecha_diagnostico'  => 'nullable|date',
            'sintomas.*.score_nih'          => 'nullable|integer|min:0',

            // Relaciones opcionales (ya decidimos que pueden ser nulas)
            'estado_id'         => 'nullable|exists:estados,id',
            'comienzo_id'       => 'nullable|exists:comienzos,id',
            'infeccion_id'      => 'nullable|exists:infeccions,id',

            // En diagnósticos manuales puede no existir regla asociada
            'regla_decision_id' => 'nullable|exists:regla_decisions,id',
        ];

        // Si el usuario es médico:
        if ($this->user()->es_medico) {
            // El médico que crea el diagnóstico debe ser él mismo
            $rules['medico_id'] = [
                'required',
                'exists:medicos,id',
                Rule::in($this->user()->medico->id),
            ];

            // El médico debe elegir el paciente sobre el que realiza el diagnóstico
            $rules['paciente_id'] = [
                'required',
                'exists:pacientes,id',
            ];
        }

        // Si el usuario es paciente (opcional, por si decides permitirlo en el futuro):
        if ($this->user()->es_paciente) {
            // No exigimos paciente_id en el request: se tomará de $this->user()->paciente_id en el controlador
            // No añadimos regla aquí a propósito para no exponer ese campo.
        }

        // Importante: no validamos 'origen' ni 'origen_id' desde el request.
        // El origen del diagnóstico (manual/inferido) lo establece el backend.

        return $rules;
    }
}
