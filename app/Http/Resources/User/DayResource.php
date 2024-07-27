<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DayResource extends JsonResource
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
            'date' => $this->date,
            'trip_id' => $this->trip_id,
            'day_id' => $this->day_id,
            'days' => FacilityDayResource::collection($this->facilityInDay),
        ];
    }
}
