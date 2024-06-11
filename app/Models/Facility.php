<?php

namespace App\Models;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facility extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'photo',
        'lat',
        'long',
        'type',
        'price_per_person',
        'bio',
        'number_of_places_available',
        'country_id',
        'profits',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
