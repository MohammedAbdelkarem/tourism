<?php

namespace App\Models;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guides_backups extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'price_per_person_one_day',
        'father_name',
        'mother_name',
        'unique_id',
        'birth_place',
        'birth_date',
        'wallet',
        'photo',
        'can_change_id',
        'accept_by_admin',
    ];

    public function scopeGuideEmail($query)
    {
        return $query->where('email' , guide_email());
    }

     
    public function trip()
    {
      return $this->hasMany(Trip::class, 'guide_backup_id', 'id');
    }
    
}
