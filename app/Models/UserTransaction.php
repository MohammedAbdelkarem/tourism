<?php

namespace App\Models;

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
}
