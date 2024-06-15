<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet',
        'date',
        'type',
        'amount',
        'user_id',
        'reservation_id',
        'admin_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    } 
}
