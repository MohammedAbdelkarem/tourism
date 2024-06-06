<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'photo',
        'lat',
        'long',
        'price_per_person',
        'bio',
        'number_of_places',
        'type',
        'country_id',
        'profits',
    ];

    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }
}
