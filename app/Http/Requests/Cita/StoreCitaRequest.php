<?php

namespace App\Http\Requests\Cita;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCitaRequest extends FormRequest
{
    protected function failedAuthorization()
    {
        $user = $this->user();

        if ($user?->es_paciente && optional($user->paciente)->medico_id === null) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'cita' => 'No tienes un médico asignado. Contacta con el centro para asignación.',
            ]);
        }
        throw \Illuminate\Validation\ValidationException::withMessages([
            'cita' => 'Ya tiene una solicitud pendiente. Espera respuesta del equipo médico antes de enviar otra.',
        ]);
    }

    public function authorize(): bool
    {
        /*dd('',auth()->user()->id);
        //return true;
        return auth()->check() && (auth()->user()->es_paciente || auth()->user()->es_medico);*/

        //Comprobamos si existen solicitudes de envío de cita para ese paciente.
        $user = $this->user();

        if (!$user)
            return false;

        if ($user->es_medico)
            return true;

        if ($user->es_paciente) {
            $pacienteId = optional($user->paciente)->id;
            if (!$pacienteId)
                return false;

            $tienePendiente = \App\Models\Cita::query()
                ->where('paciente_id', $pacienteId)
                ->where('estado', 'pendiente')
                ->exists();

            return !$tienePendiente;
        }

        return false;
    }

    public function rules(): array
    {
        $user = $this->user();

        $motivos = [
            'Seguimiento de síntomas',
            'Revisión de tratamiento',
            'Revisión de diagnósticos recientes',
            'Consulta sobre resultados de pruebas',
            'Solicitud de renovación/ajuste de medicación',
            'Gestión administrativa',
            'Otro',
        ];

        if ($user->es_paciente) {
            return [
                'motivo' => ['required', 'string', Rule::in($motivos)],
                'motivo_detalle' => ['nullable', 'string', 'max:2000', 'required_if:motivo,Otro'],
                'preferencia_fecha_hora' => ['nullable', 'date'],

                // Blindaje real
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
