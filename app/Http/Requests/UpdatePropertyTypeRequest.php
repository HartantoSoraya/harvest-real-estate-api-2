<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:property_types,name',
            'slug' => 'nullable|string|max:255|unique:property_types,slug',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('slug')) {
            $this->merge(['slug' => null]);
        }
    }
}
