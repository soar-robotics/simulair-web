<?php

namespace App\Services;

use App\Models\MongoDB\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class EnvironmentService
{
    protected $users;

    public function __construct(User $users)
    {
        $this->users = $users;
    }

    public function getEnvironmentsForUser(string $userId, array $filters = [])
    {
        $user = $this->users->find($userId);

        $environments = $user->environments();

        if ($queryFilter = Arr::get($filters, 'query', null)) {
            $environments = $environments->where('name', 'LIKE', '%' . $queryFilter . '%');
        }

        $environments = $environments->with('user')->get();

        return $environments;
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
