<?php

namespace App\Http\Resources\Admin;

use App\Models\UsersBackup;
use Illuminate\Http\Request;
use App\Models\UserTransaction;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
   { $userTransaction = UserTransaction::where('user_id', $this->resource->id)
    ->latest()
    ->first();
$wallet = $userTransaction ? $userTransaction->wallet : null;

return [
    'id' => $this->resource->id,
    'name' => $this->resource->name,
    'photo' => $this->resource->photo,
    'wallet' => $wallet,
];}
}
