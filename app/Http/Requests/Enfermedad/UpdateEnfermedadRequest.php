<?php

namespace App\Http\Requests\Enfermedad;

use App\Models\Enfermedad;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEnfermedadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $enfermedad = Enfermedad::find($this->route('enfermedad'))->first();
        return $enfermedad && $this->user()->can('update', $enfermedad);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo_trasplante'=> 'required|string',
            'nombre_enfermedad'=> 'required|string',
            'fecha_trasplante'=> 'required|date',
            'origen_trasplante'=> 'required|string',
            'tipo_acondicionamiento'=> 'string',
            'seropositividad_donante' => 'string',
            'seropositividad_receptor' => 'string',
        ];
    }
}
