<?php

namespace App\Http\Requests\Medicamento;

use App\Models\Medicamento;
use Illuminate\Foundation\Http\FormRequest;

class StoreMedicamentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Medicamento::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'miligramos' => 'required|numeric|min:0',
        ];
    }
}
