<?php

namespace App\Http\Requests\Prueba;

use App\Models\Prueba;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePruebaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Prueba::class);
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
