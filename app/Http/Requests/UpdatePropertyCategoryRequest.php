<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255|string|unique:property_categories,name,'.$this->route('id'),
            'icon' => 'required|max:255|string',
            'slug' => 'nullable|max:255|string|unique:property_categories,slug,'.$this->route('id'),
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('slug')) {
            $this->merge(['slug' => null]);
        }
    }
}
