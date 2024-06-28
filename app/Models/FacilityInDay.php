<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Laravel\Prompts\note;

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




    public function FacilityDay()
    {
        return $this->belongsTo(FacilityDay::class);
    }
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
