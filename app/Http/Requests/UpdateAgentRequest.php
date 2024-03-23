<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAgentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'code' => 'required|string|max:255|unique:agents,code,'.$this->route('id'),
            'name' => 'required|max:255|string',
            'description' => 'required|max:255|string',
            'specialization' => 'required|max:255|string',
            'email' => 'required|max:255|email',
            'phone_number' => 'required|max:255|string',
            'facebook' => 'nullable|max:255|string',
            'twitter' => 'nullable|max:255|string',
            'instagram' => 'nullable|max:255|string',
            'linkedin' => 'nullable|max:255|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'slug' => 'nullable|max:255|string|unique:agents,slug,'.$this->route('id'),
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('slug')) {
            $this->merge(['slug' => null]);
        }
    }
}
