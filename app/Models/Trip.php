<?php

namespace App\Models;

use App\Models\Country;
use App\Models\FacilityDay;
use App\Models\Reservatoin;
use App\Models\Photo;
use App\Models\Guides_backups;
use App\Models\TripRate;
use App\Models\TripComment;
use App\Models\AvailableGuide;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'lat',
        'long',
        'guide_backup_id',
        'country_id',
    ];

    public function modify($amount , $column , $char)
    {
        $this->$column += ($char == '+') ? $amount : -$amount;
        $this->save();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);

    }

    public function facilityDay()
    {
      return $this->hasMany(FacilityDay::class, 'trip_id', 'id');
    } 


    public function reservatoin()
    {
      return $this->hasMany(Reservatoin::class, 'trip_id', 'id');
    }


    public function photo()
    {
      return $this->hasMany(Photo::class, 'trip_id', 'id');
    }

    public function trip_rates()
    {
      return $this->hasMany(TripRate::class, 'trip_id', 'id');
    }

    public function trip_comment()
    {
      return $this->hasMany(TripComment::class, 'trip_id', 'id');
    }

    public function Guides_backups()
    {
        return $this->belongsTo(Guides_backups::class , 'guide_backup_id');

    }

    public function availableGuide()
    {
        return $this->hasOne(AvailableGuide::class);
    }

    public function favourites()
    {
        return $this->belongsToMany(User::class, 'favourites');
    }

    //scopes
    public function scopeOffer($query)
    {
        return $query->where('offer_ratio' , '!=' , '0');
    }
    public function scopeActive($query)
    {
        return $query->where('status' , 'active');
    }
    public function scopeFavourite($query)
    {
        return $query->with(['favourites' => function ($query) {
          $query->where('user_id', user_id());
      }]);
    }

}

