<?php

namespace App\Http\Requests\Prueba;

use App\Models\Prueba;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePruebaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Prueba::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        // Si viene por ruta nested /pacientes/{paciente}/pruebas, NO exigir paciente_id en el body
        $pacienteRoute = $this->route('paciente'); // puede ser Paciente o string/int según binding

        $requierePacienteIdEnBody = ($user && $user->es_medico && empty($pacienteRoute));

        return [
            'nombre' => 'required|string|max:255',
            'tipo_prueba_id' => 'nullable|exists:tipo_pruebas,id',
            'fecha' => 'nullable|date',
            'resultado' => 'nullable|string',
            'comentario' => 'nullable|string',

            // Si es paciente -> nullable (lo fuerza el controller)
            // Si es médico y NO viene nested -> required
            // Si es médico y viene nested -> nullable
            'paciente_id' => ($user?->es_paciente ? 'nullable' : ($requierePacienteIdEnBody ? 'required' : 'nullable'))
                . '|exists:pacientes,id',
        ];
    }
}
