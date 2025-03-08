<?php

namespace App\Http\Requests\Organo;

use App\Models\Organo;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $organo = Organo::find($this->route('organo'))->first();
        return $organo && $this->user()->can('update', $organo);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255'

        ];
    }
}
