<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility_Day_Notes extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'facility__day_id',
    ];
}
