<?php

namespace App\Models;

use App\Models\Trip;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'photo',
        'bio',
    ];

    public function facility()
    {
      return $this->hasMany(Facility::class, 'country_id', 'id');
    }
  
    public function trip()
    {
      return $this->hasMany(Trip::class, 'country_id', 'id');
    }
}
