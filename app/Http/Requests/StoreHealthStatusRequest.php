<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreHealthStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:health_statuses,name'],
            'slug' => ['required', 'string'],
        ];
    }
    
    protected function prepareForValidation()
    {
        if ($this->has('name')) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }
    }
}
