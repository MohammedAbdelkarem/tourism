<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate',
        'user_backup_id',
        'facility_id',
    ];
}
