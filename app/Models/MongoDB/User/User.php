<?php

namespace App\Models\MongoDB\User;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\URL;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

use App\Models\MongoDB\Simulation\Simulation;
use App\Models\MongoDB\Robot\Robot;
use App\Models\MongoDB\Environment\Environment;
use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Mongodb\Relations\HasMany;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = [
        'email_verified_at', 'google_auth_at'
    ];
    protected $fillable = [
        'first_name', 'last_name', 'username', 'company', 'email'
    ];
    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'email_verified_at' => null,
        'profile_image' => null
    ];

    protected $hidden = [
        'password'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getIsVerifiedAttribute()
    {
        return ($this->email_verified_at) ? 1 : 0;
    }

    public function getImageUrlAttribute()
    {
        if (!$this->profile_image) {
            return null;
        }

        return URL::signedRoute('users.user.image', ['id' => $this->id, 'path' => $this->profile_image]);
    }

    public function refreshTokens() {
        return $this->hasMany(RefreshToken::class);
    }

    public function simulations()
    {
        return $this->hasMany(Simulation::class);
    }

    public function environments()
    {
        return $this->hasMany(Environment::class);
    }

    public function robots()
    {
        return $this->hasMany(Robot::class);
    }

    public function ownsRobot($robotId): bool
    {
        if ($this->robots()->find($robotId)) return true;
        return false;
    }

    public function ownsEnvironment($environmentId): bool
    {
        if ($this->environments()->find($environmentId)) return true;
        return false;
    }

    public function ownsSimulation($simulationId): bool
    {
        if ($this->simulations()->find($simulationId)) return true;
        return false;
    }
}
