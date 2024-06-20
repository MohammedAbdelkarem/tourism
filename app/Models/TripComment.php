<?php

namespace App\Models;

use App\Models\Trip;
use App\Models\UsersBackup;
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

       
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    } 

    public function usersBackup()
    {
        return $this->belongsTo(UsersBackup::class , 'user_backup_id');
    } 

}
