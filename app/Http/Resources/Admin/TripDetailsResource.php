<?php

namespace App\Http\Resources\Admin;

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
    {  return [
        'id' => $this->resource->id,
        'name' => $this->resource->name,
        'photo' => $this->resource->photo,
        'lat' => $this->resource->lat,
        'long' => $this->resource->long,
        'bio' => $this->resource->bio,
        'type' => $this->resource->type,
        'price_per_one_old' => $this->resource->price_per_one_old,
        'price_per_one_new' => $this->resource->price_per_one_new,
        'total_price' => $this->resource->total_price,
        'status' => $this->resource->status,
        'start_date' => $this->resource->start_date,
        'end_date' => $this->resource->end_date,
        'number_of_filled_places' => $this->resource->number_of_filled_places,
        'number_of_available_places' => $this->resource->number_of_available_places,
        'number_of_original_places' => $this->resource->number_of_original_places,
        'offer_ratio' => $this->resource->offer_ratio,
        'guide_backup_id' => $this->resource->guide_backup_id,
        'country_id' => $this->resource->country_id,
    ];
    }
}

