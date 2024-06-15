<?php

namespace App\Models;

use App\Models\FacilityDay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityDayNotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'facility_day_id',
    ];

    public function FacilityDay()
    {
        return $this->belongsTo(FacilityDay::class);
    } 
  
}
