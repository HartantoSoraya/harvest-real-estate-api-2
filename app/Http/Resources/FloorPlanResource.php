<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FloorPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'property_id' => $this->property_id,
            'sort' => $this->sort,
            'title' => $this->title,
            'image' => $this->image,
            'image_url' => $this->image ? asset('storage/'.$this->image) : '',
        ];
    }
}
