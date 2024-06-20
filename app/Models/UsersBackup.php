<?php

namespace App\Models;

use App\Models\TripRate;
use App\Models\TripComment;
use App\Models\FacilityComment;
use App\Models\FacilityRate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersBackup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'photo',
        'phone',
        'password',
        'wallet',
        'active',
    ];

    public function scopeUserEmail($query)
    {
        return $query->where('email' , user_email());
    }

    public function trip_rates()
    {
      return $this->hasMany(TripRate::class, 'user_backup_id', 'id');
    }

    public function trip_comment()
    {
      return $this->hasMany(TripComment::class, 'user_backup_id', 'id');
    }


    public function facilityRate()
    {
      return $this->hasMany(FacilityRate::class, 'user_backup_id', 'id');
    }


}
