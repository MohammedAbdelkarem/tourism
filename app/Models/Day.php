<?php

namespace App\Models;

use App\Models\FacilityDay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];


    public function facilityDay()
{
  return $this->hasMany(FacilityDay::class, 'day_id', 'id');
}

}
