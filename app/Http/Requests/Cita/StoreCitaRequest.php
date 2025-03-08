<?php

namespace App\Http\Requests\Cita;

use App\Models\Cita;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCitaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Cita::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->user()->es_paciente)
            return [
                'fecha_hora' => 'required|date|after:yesterday',
                'medico_id' => 'required|exists:medicos,id',
                'paciente_id' => ['required', 'exists:pacientes,id', Rule::in($this->user()->paciente->id)]
            ];
        return [
            'fecha_hora' => 'required|date|after:yesterday',
            'medico_id' => 'required|exists:medicos,id',
            'paciente_id' => 'required|exists:pacientes,id'
        ];
    }
}
