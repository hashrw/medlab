<?php

namespace App\Http\Requests\Paciente;

use App\Models\Paciente;
use App\Rules\Nuhsa;
use Illuminate\Foundation\Http\FormRequest;

class StorePacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Paciente::class) ?? false;
    }

    public function rules(): array
    {
        return [
            // PACIENTE
            'nuhsa' => ['required', 'string', 'size:12', new Nuhsa],
            'fecha_nacimiento' => ['required', 'date'],
            'sexo' => ['required', 'in:M,F'],
            'peso' => ['nullable', 'numeric', 'min:1', 'max:500'],
            'altura' => ['nullable', 'numeric', 'min:30', 'max:300'],

            // ASIGNACIÓN
            'medico_id' => ['required', 'exists:medicos,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $nuhsa = $this->input('nuhsa');

        if ($nuhsa !== null) {
            $nuhsa = strtoupper(trim($nuhsa));
            $nuhsa = preg_replace('/[\s\-]/', '', $nuhsa);

            if (preg_match('/^\d{10}$/', $nuhsa)) {
                $nuhsa = 'AN' . $nuhsa;
            }

            $this->merge(['nuhsa' => $nuhsa]);
        }
    }

}
