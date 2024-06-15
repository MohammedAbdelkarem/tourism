<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'user_backup_id',
        'facility_id',
    ];
}
