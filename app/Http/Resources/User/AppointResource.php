<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointResource extends JsonResource
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
            'total_price' => $this->total_price,
            'number_of_places' => $this->number_of_places,
            'date_of_last_modification' => $this->updated_at,
            'trip_id' => $this->trip->id,
            'trip_name' => $this->trip->name,
            'trip_bio' => $this->trip->bio,
            'trip_rate' => $this->trip->rate,
            'trip_price' => $this->trip->price_per_one_new,
            'trip_status' => $this->trip->status,
        ];
    }
}
