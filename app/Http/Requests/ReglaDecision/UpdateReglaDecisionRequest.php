<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReglaDecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Política mínima: solo médicos editan
        return $this->user() && (int) $this->user()->tipo_usuario_id === 1;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['nullable', 'string', 'max:255'],
            'prioridad' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'tipo_recomendacion' => ['nullable', 'string', 'max:255'],
            'condiciones' => ['nullable'],
            'diagnostico' => ['nullable'],
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach (['condiciones', 'diagnostico'] as $field) {
            $value = $this->input($field);

            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->merge([$field => $decoded]);
                }
            }
        }
    }
}
