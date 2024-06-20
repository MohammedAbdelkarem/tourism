<?php

namespace App\Models;

use App\Models\Country;
use App\Models\FacilityDay;
use App\Models\FacilityComment;
use App\Models\FacilityRate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'photo',
        'lat',
        'long',
        'bio',
        'number_of_available_places',
        'price_per_person',
        'profits',
        'total_rate',
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function facilityDay()
    {
      return $this->hasMany(FacilityDay::class, 'facility_id', 'id');
    }



    public function facilityRate()
    {
      return $this->hasMany(FacilityRate::class, 'facility_id', 'id');
    }
}