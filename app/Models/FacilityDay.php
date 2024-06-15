<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'start_time',
        'end_time',
        'day_id',
        'facility_id',
        'trip_id',
    ];
}
