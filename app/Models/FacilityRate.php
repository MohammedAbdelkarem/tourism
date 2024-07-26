<?php

namespace App\Models;

use App\Models\Facility;
use App\Models\UsersBackup;
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

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    } 

    
    public function usersBackup()
    {
        return $this->belongsTo(UsersBackup::class , 'user_backup_id');
    } 
    
}
