<?php

namespace App\Http\Resources\Guide;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'unique_id' => $this->unique_id,
            'email' => $this->email,
            'birth_place' => $this->birth_place,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone,
            'status' => $this->status,
            'price' => $this->price_per_person_one_day,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'photo' => $this->photo,
            'wallet' => $this->wallet,
        ];
    }
}
