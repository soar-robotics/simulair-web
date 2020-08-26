<?php

namespace App\Models\MongoDB\Simulation;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\MongoDB\User\User;
use App\Models\MongoDB\Robot\Robot;
use App\Models\MongoDB\Environment\Environment;

class Simulation extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'simulations';
    protected $dates = [];
    protected $fillable = [
        'robot_id', 'environment_id', 'name', 'status',
        'thumbnail', 'description'
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'status' => self::STATUS_RUNNING,
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_RUNNING = 'running';
    const STATUS_PAUSED = 'paused';
    const STATUS_STOPPED = 'stopped';

    const ACCESS_LEVEL_PRIVATE = "private";

    public static function availableStatuses(): array
    {
        return [self::STATUS_PAUSED, self::STATUS_RUNNING, self::STATUS_STOPPED];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function environment()
    {
        return $this->belongsTo(Environment::class);
    }

    public function robot()
    {
        return $this->belongsTo(Robot::class);
    }
}
