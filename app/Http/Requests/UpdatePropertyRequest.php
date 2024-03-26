<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255|string',
            'description' => 'required|max:255|string',
            'loc_city' => 'required|max:255|string',
            'loc_latitude' => 'required|max:255|string',
            'loc_longitude' => 'required|max:255|string',
            'loc_address' => 'required|max:255|string',
            'loc_state' => 'required|max:255|string',
            'loc_zip' => 'required|min:0|string',
            'loc_country' => 'required|max:255|string',
            'price' => 'required|min:0|numeric',
            'agent_id' => 'required|max:255|string|exists:agents,id',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_sold' => 'nullable|boolean',
            'is_rented' => 'nullable|boolean',
            'offer_type' => 'nullable|max:255|string',
            'slug' => 'nullable|max:255|string|unique:properties,slug,'.$this->route('property'),

            'amenities' => 'required|array',
            'amenities.*' => 'exists:property_amenities,id',

            'categories' => 'required|array',
            'categories.*' => 'exists:property_categories,id',

            'types' => 'required|array',
            'types.*' => 'exists:property_types,id',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'deleted_images_ids' => 'nullable|array',
            'deleted_images_ids.*' => 'exists:property_images,id',

            'floor_plans' => 'nullable|array',
            'floor_plans.*.id' => 'nullable|string|exists:floor_plans,id',
            'floor_plans.*.sort' => 'required|integer',
            'floor_plans.*.title' => 'required|max:255|string',
            'floor_plans.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deleted_floor_plans_ids' => 'nullable|array',
            'deleted_floor_plans_ids.*' => 'exists:floor_plans,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('slug')) {
            $this->merge(['slug' => null]);
        }

        if (! $this->has('images')) {
            $this->merge(['images' => []]);
        }

        if (! $this->has('deleted_images_ids')) {
            $this->merge(['deleted_images_ids' => []]);
        }

        if (! $this->has('floor_plans')) {
            $this->merge(['floor_plans' => []]);
        } else {
            $floorPlans = $this->floor_plans;
            foreach ($floorPlans as $index => $floorPlan) {
                if (! isset($floorPlan['id'])) {
                    $floorPlans[$index]['id'] = null;
                }
            }
            $this->merge(['floor_plans' => $floorPlans]);
        }

        if (! $this->has('deleted_floor_plans_ids')) {
            $this->merge(['deleted_floor_plans_ids' => []]);
        }
    }
}
