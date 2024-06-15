<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'user_backup_id',
        'trip_id',
    ];
}
