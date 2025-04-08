<?php

namespace App\Http\Requests\Especialidad;

use App\Models\Especialidad;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEspecialidadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $especialidad = Especialidad::find($this->route('especialidad'))->first();
        return $especialidad && $this->user()->can('update', $especialidad);
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
        ];
    }
}
