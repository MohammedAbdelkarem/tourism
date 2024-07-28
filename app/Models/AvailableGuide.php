<?php

namespace App\Models;

use App\Models\Guide;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'accept_trip',
        'trip_id',
        'guide_id',
    ];

    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    } 

    //scopes
    public function scopeAcceptedTrips($query) {
        return $query->where('accept_trip', 'accepted')
                     ->with('trip');
    }

    
    public function scopeRejectedTrips($query) {
        return $query->where('accept_trip', 'rejected')
                     ->with('trip');
    }
    public function scopeNullTrips($query) {
        return $query->where('accept_trip', null)
                     ->with('trip');
    }
    
    public function scopeAccepted($query) {
        return $query->where('accept_trip', 'accepted');
    }
}
