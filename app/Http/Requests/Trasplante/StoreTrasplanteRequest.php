<?php

namespace App\Http\Requests\Trasplante;

use App\Models\Trasplante;
use Illuminate\Foundation\Http\FormRequest;

class StoreTrasplanteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Trasplante::class);

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
