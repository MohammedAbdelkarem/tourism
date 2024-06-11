<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility_Day extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'day_id',
        'facility_id',
        'trip_id',
        'start_time',
        'end_time',
    ];
}
