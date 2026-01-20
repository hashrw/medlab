<?php

namespace App\Http\Requests\Cita;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->es_paciente || auth()->user()->es_medico);
    }

    public function rules(): array
    {
        $user = $this->user();

        $motivos = [
            'Consulta sobre diagnóstico',
            'Consulta sobre tratamiento',
            'Consulta sobre resultados de pruebas',
            'Revisión / seguimiento',
            'Otro',
        ];

        if ($user->es_paciente) {
            return [
                'motivo' => ['required', 'string', Rule::in($motivos)],
                'motivo_detalle' => ['nullable', 'string', 'max:2000', 'required_if:motivo,Otro'],
                'preferencia_fecha_hora' => ['nullable', 'date'],

                // Blindaje: el paciente no escribe columnas de gestión
                'fecha_hora' => ['prohibited'],
                'medico_id' => ['prohibited'],
                'paciente_id' => ['prohibited'],
                'estado' => ['prohibited'],
                'comentario_medico' => ['prohibited'],
                'respondida_at' => ['prohibited'],
            ];
        }

        // Médico crea cita real (backoffice)
        return [
            'fecha_hora' => ['required', 'date'],
            'medico_id' => ['required', 'exists:medicos,id'],
            'paciente_id' => ['required', 'exists:pacientes,id'],

            'estado' => ['nullable', 'string', Rule::in(['pendiente', 'aceptada', 'rechazada', 'cancelada'])],

            'motivo' => ['nullable', 'string', 'max:120'],
            'motivo_detalle' => ['nullable', 'string', 'max:2000'],
            'preferencia_fecha_hora' => ['nullable', 'date'],
            'comentario_medico' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'motivo_detalle.required_if' => 'Indica el detalle del motivo si has seleccionado "Otro".',
        ];
    }
}
