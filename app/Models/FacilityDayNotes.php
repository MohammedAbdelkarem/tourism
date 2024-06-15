<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityDayNotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'facility_day_id',
    ];
}
