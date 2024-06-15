<?php

namespace App\Models;

use App\Models\Guide;

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

    public function guide()
    {
        return $this->belongsTo(Guide::class);
    } 
}
