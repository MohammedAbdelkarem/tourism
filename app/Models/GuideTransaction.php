<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuideTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet',
        'date',
        'amount',
        'guide_id',
    ];
}
