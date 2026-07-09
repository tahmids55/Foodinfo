<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNutrientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:nutrients,name,' . $this->nutrient],
            'type' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
        ];
    }
}
