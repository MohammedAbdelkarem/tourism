<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'photo' => $this->photo,
            'lat' => $this->lat,
            'long' => $this->long,
            'bio' => $this->bio,
            'number_of_available_places' => $this->number_of_available_places,
            'price_per_person' => $this->price_per_person,
            'profits' => $this->profits,
            'total_rate' => $this->total_rate,
            'country_id' => $this->country_id,
            'rates' => RateResource::collection($this->facilityRate),
        ];
    }
}
