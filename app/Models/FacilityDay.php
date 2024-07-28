<?php

namespace App\Models;

use App\Models\Trip;
use App\Models\Day;
use App\Models\Facility;
use App\Models\FacilityDayNotes;
use App\Models\FacilityInDay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        // 'start_time',
        // 'end_time',
        'day_id',
        'trip_id',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }


    public function Day()
    {
        return $this->belongsTo(Day::class , 'day_id');
    } 

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    } 
    public function facilityInDay()
    {
        return $this->hasMany(FacilityInDay::class);
    } 


    
  
   
}
