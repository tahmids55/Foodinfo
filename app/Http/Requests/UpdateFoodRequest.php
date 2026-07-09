<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFoodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'scientific_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'serving_size' => ['required', 'string', 'max:100'],
            'calories' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            
            // Arrays for relations
            'nutrients' => ['nullable', 'array'],
            'nutrients.*' => ['nullable', 'numeric', 'min:0'],
            
            'health_statuses' => ['nullable', 'array'],
            'health_statuses.*' => ['exists:health_statuses,id'],
        ];
    }
}
