<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\GuideTransaction;
use App\Models\AvailableGuide;

class Guide extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
        'photo',
        'can_change_unique_id',
        'accept_by_admin',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeGuideEmail($query)
    {
        return $query->where('email' , guide_email());
    }
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }    
    
    public function guidTransaction()
    {
    return $this->hasMany(GuideTransaction::class, 'guide_id', 'id');
    }

    public function availableGuide()
    {
    return $this->hasMany(AvailableGuide::class, 'guide_id', 'id');
    }
    
    function scopeAvailable($query) {
        return $query->where('status', 'available')
                         ->where('accept_by_admin', 'accepted');
    }
}