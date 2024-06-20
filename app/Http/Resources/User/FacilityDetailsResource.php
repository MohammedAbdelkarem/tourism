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
            'name' => $this->name,
            'photo' => $this->photo,
            'rate' => $this->rate,
            'bio' => $this->bio,
            'price_per_person' => $this->price_per_person,
            'comments' => CommentResource::collection($this->facilityComment),
        ];
    }
}
