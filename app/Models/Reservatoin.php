<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservatoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_price',
        'number_of_places',
        'user_id',
        'trip_id',
    ];
}
