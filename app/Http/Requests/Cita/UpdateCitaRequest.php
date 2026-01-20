<?php

namespace App\Http\Requests\Cita;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // MVP: solo médico edita/gestiona
        return auth()->check() && auth()->user()->es_medico;
    }

    public function rules(): array
    {
        return [
            'fecha_hora' => ['nullable', 'date'],
            'medico_id' => ['nullable', 'exists:medicos,id'],
            'paciente_id' => ['nullable', 'exists:pacientes,id'],

            'estado' => ['required', 'string', Rule::in(['pendiente', 'aceptada', 'rechazada', 'cancelada'])],

            'comentario_medico' => ['nullable', 'string', 'max:2000'],
            'respondida_at' => ['nullable', 'date'],

            'motivo' => ['nullable', 'string', 'max:120'],
            'motivo_detalle' => ['nullable', 'string', 'max:2000'],
            'preferencia_fecha_hora' => ['nullable', 'date'],
        ];
    }
}
