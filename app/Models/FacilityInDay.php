<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityInDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        'facility_id',
        'facility_day_id',
        'note',
    ];
}
