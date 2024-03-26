<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'loc_city' => $this->loc_city,
            'loc_latitude' => $this->loc_latitude,
            'loc_longitude' => $this->loc_longitude,
            'loc_address' => $this->loc_address,
            'loc_state' => $this->loc_state,
            'loc_zip' => $this->loc_zip,
            'loc_country' => $this->loc_country,
            'price' => $this->price,
            'price_label' => $this->price_label,
            'agent_id' => $this->agent_id,
            'agent' => $this->agent,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'is_sold' => $this->is_sold,
            'is_rented' => $this->is_rented,
            'offer_type' => $this->offer_type,
            'amenities' => PropertyAmenityResource::collection($this->amenities),
            'types' => PropertyTypeResource::collection($this->types),
            'categories' => PropertyCategoryResource::collection($this->categories),
            'images' => PropertyImageResource::collection($this->images),
            'first_image' => PropertyImageResource::make($this->images->first()),
            'floor_plans' => FloorPlanResource::collection($this->floorPlans),
        ];
    }
}
