<?php

namespace App\Models;

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
        'country_id',
    ];
}
