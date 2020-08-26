<?php

namespace App\Models\MongoDB\Environment;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\MongoDB\User\User;
use App\Models\MongoDB\Robot\Simulation;

class Environment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'environments';
    protected $dates = [];
    public $timestamps = true;

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
