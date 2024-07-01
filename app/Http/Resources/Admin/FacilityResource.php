<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {  return [
        'id' => $this->resource->id,
        'name' => $this->resource->name,
        'photo' => $this->resource->photo,
        'lat' => $this->resource->lat,
        'long' => $this->resource->long,
        'bio' => $this->resource->bio,
        'type' => $this->resource->type,
        'number_of_places' => $this->resource->number_of_places,
        'price_per_person' => $this->resource->price_per_person,
        'country_id' => $this->resource->country_id,
        'total_rate' => $this->resource->total_rate,
        
    ];
    }
}
