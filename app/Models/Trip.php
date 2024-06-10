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
        'bio',
        'photo',
        'rate',
        'price_per_one_old',
        'price_per_one_new',
        'total_price',
        'status',
        'start_date',
        'end_date',
        'number_of_filled_places',
        'number_of_available_places',
        'number_of_original_places',
        'offer_ratio',
        'guide_id',
        'country_id',
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
