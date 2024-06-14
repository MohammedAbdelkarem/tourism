<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'accept_trip',
        'trip_id',
        'guide_id',
    ];
}
