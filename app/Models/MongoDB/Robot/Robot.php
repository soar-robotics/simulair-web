<?php

namespace App\Models\MongoDB\Robot;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\MongoDB\User\User;
use App\Models\MongoDB\Robot\Simulation;

class Robot extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'robots';
    protected $dates = [];
    public $timestamps = true;
    protected $fillable = [
        'user_id', 'name', 'thumbnail', 'description', 'access_level'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const ACCESS_LEVEL_PRIVATE = "private";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function simulations()
    {
        return $this->hasMany(Simulation::class);
    }
}
