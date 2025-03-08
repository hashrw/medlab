<?php

namespace App\Http\Requests\Medico;

use App\Models\Medico;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $medico = Medico::find($this->route('medico'))->first();
        return $medico && $this->user()->can('update', $medico);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'numeric|max:255',
            'residente' => 'required|boolean',
            'especialidad_id' => 'required|exists:especialidads,id',
        ];
    }
}
