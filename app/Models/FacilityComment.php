<?php

namespace App\Models;

use App\Models\UsersBackup;
use App\Models\Facility;
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

    public function usersBackup()
    {
        return $this->belongsTo(UsersBackup::class);
    } 
    
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    } 
}
