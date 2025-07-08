<?php

namespace App\Http\Requests\prueba;

use App\Models\Prueba;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePruebaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $prueba = Prueba::find($this->route('prueba'))->first();
        return $prueba && $this->user()->can('update', $prueba);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fecha' => 'required|date|after:yesterday',
            'nombre' => 'required|string',
            'tipo_prueba' => 'required|string',
            'resultado' => 'required|string',
            'comentario' => 'required|string',

        ];
    }
}
