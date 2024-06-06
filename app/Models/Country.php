<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'photo',
    ];

    public function facility()
    {
      return $this->hasMany(\App\Models\Facility::class, 'country_id', 'id');
    }
  
    public function trip()
    {
      return $this->hasMany(\App\Models\Trip::class, 'country_id', 'id');
    }
}
