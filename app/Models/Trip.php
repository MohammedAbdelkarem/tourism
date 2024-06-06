<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use App\Models\Guide;
class Trip extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'photo',
        'bio',
        'rate',
        'price_per_one_old',
        'status',
        'price_per_person_new',
        'offers_ratio',
        'total_price',
        'first_date',
        'end_date',
        'num_of_person',
        'num_of_places',
        'country_id',
        'guide_id',
    ];


    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }
}
