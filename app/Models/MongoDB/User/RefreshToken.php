<?php

namespace App\Models\MongoDB\User;

use Jenssegers\Mongodb\Eloquent\Model;

class RefreshToken extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'refresh_tokens';
    protected $dates = [
        'expiration',
        'used_at',
    ];
    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'req_platform' => null,
        'used_at' => null,
    ];
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeByValue($query, $value) {
        return $query->where("value", $value);
    }

    public function scopeUnused($query) {
        return $query->whereNull("used_at");
    }
}
