<?php

namespace App\Http\Requests\Sintoma;

use App\Models\Sintoma;
use App\Models\Tratamiento;
use Illuminate\Foundation\Http\FormRequest;

class StoreSintomaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Sintoma::class);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sintoma' => 'required|string',
            'manif_clinica' => 'string',
            'organo' => 'string',
        ];

    }
}
