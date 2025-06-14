<?php

namespace App\Http\Requests\Sintoma;

use App\Models\Sintoma;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSintomaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $sintoma = Sintoma::find($this->route('sintoma'))->first();
        return $sintoma && $this->user()->can('update', $sintoma);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sintoma' => 'string',
            'manif_clinica' => 'string',
            'organo' => 'string',
        ];
    }
}
