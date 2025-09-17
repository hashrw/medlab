<?php

namespace App\Http\Requests\Trasplante;

use App\Models\Trasplante;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTrasplanteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $trasplante = Trasplante::find($this->route('trasplante'))->first();
        return $trasplante && $this->user()->can('update', $trasplante);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo_trasplante' => 'string',
            'fecha_trasplante' => 'date',
            'origen_trasplante' => 'string',
            'identidad_hla' => 'required|string',
            'tipo_acondicionamiento' => 'string',
            'seropositividad_donante' => 'string',
            'seropositividad_receptor' => 'string',
            'paciente_id' => 'exists:pacientes,id',

        ];
    }
}
