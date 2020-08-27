<?php

namespace App\Services;

use App\Models\MongoDB\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class RobotService
{
    protected $users;

    public function __construct(User $users)
    {
        $this->users = $users;
    }

    public function getRobotsForUser(string $userId, array $filters = [])
    {
        $user = $this->users->find($userId);

        $robots = $user->robots();

        if ($queryFilter = Arr::get($filters, 'query', null)) {
            $robots = $robots->where('name', 'LIKE', '%' . $queryFilter . '%');
        }

        $robots = $robots->with('user')->get();

        return $robots;
    }

    /*
    private function applyFilters(Builder $queryBuilder, array $filters)
    {
        if ($queryFilter = Arr::get($filters, 'query', null)) {
            $queryBuilder = $queryBuilder->where('name', 'LIKE', '%' . $queryFilter . '%');
        }

        return $queryBuilder;
    }
    */
}
