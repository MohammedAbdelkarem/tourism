<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripDetailsResource extends JsonResource
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
            'bio' => $this->bio,
            'old_price' => $this->price_per_one_old,
            'price' => $this->price_per_one_new,
            'offer_ratio' => $this->offer_ratio,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'number_of_available_places' => $this->number_of_available_places,
            'guide' => $this->Guides_backups->name,
            'guide_phone' => $this->Guides_backups->phone,
            'comments' => CommentResource::collection($this->trip_comment),
        ];
    }
}
