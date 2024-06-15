<?php

namespace App\Models;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservatoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_price',
        'number_of_places',
        'user_id',
        'trip_id',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    } 

    public function user()
    {
        return $this->belongsTo(User::class);
    } 

}
