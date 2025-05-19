<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'phone',
        'age',
        'gender',
        'qualification',
        'role',
        'description',
        'email',
        'password', ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    function lessons(): hasMany {
        return $this->hasMany(Lesson::class, 'tutor_id');
    }

    // TODO: Braucht man beide Methoden?
    function studentBookings(): HasMany {
        return $this->hasMany(Booking::class, 'student_id');
    }

    function tutorBookings(): HasMany {
        return $this->hasMany(Booking::class, 'tutor_id');
    }


    /*----auth JWT----*/
    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['user' => [
            'id' => $this->id,
            'role' => $this->role
        ]];
    }
}
