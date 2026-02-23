<?php

namespace App\Http\Requests\Prueba;

use App\Models\Prueba;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePruebaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $prueba = $this->route('prueba');

        if (!$prueba instanceof Prueba) {
            $prueba = Prueba::find($prueba);
        }

        return $prueba && $this->user()->can('update', $prueba);
    }

    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|required|string|max:255',
            'tipo_prueba_id' => 'sometimes|nullable|exists:tipo_pruebas,id',
            'fecha' => 'sometimes|nullable|date',
            'resultado' => 'sometimes|nullable|string',
            'comentario' => 'sometimes|nullable|string',
        ];
    }
}
