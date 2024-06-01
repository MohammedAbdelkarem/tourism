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
        'profits',
        'price_per_person',
        'bio',
        'number_of_places',
        'type',
    ];
}
