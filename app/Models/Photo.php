<?php

namespace App\Models;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'trip_id',
    ];


    public function trip()
    {
        return $this->belongsTo(Trip::class , 'trip_id');
    } 

}
