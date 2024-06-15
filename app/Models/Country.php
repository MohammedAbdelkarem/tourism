<?php

namespace App\Models;

use App\Models\Facility;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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
