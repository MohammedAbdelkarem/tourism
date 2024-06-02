<?php

namespace App\Models;

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
    ];

    public function scopeUserEmail($query)
    {
        return $query->where('email' , user_email());
    }
}
