<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'wallet' => $this->wallet,
            'date' => $this->date,
            'type' => $this->type,
            'amount' => $this->amount,
            // 'user_id' => $this->user_id,
            'reservation_id' => ($this->type == 'add') ? null : $this->reservation_id,
            'admin_id' => ($this->type == 'dis') ? null : $this->admin_id
        ];
    }
}
