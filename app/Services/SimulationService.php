<?php

namespace App\Services;

use App\Models\MongoDB\Simulation\Simulation;
use App\Models\MongoDB\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class SimulationService
{
    protected $users;

    public function __construct(User $users)
    {
        $this->users = $users;
    }

    public function getSimulationsForUser(string $userId, array $filters = [])
    {
        $user = $this->users->find($userId);

        $simulations = $user->simulations();

        if ($queryFilter = Arr::get($filters, 'query', null)) {
            $simulations = $simulations->where('name', 'LIKE', '%' . $queryFilter . '%');
        }
        if ($statusFilter = Arr::get($filters, 'status', null)) {
            $simulations = $simulations->where('status', $statusFilter);
        }

        $simulations = $simulations->orderBy('updated_at', 'desc')->with('user')->get();

        return $simulations;
    }

    public function setStatus(string $id, string $status)
    {
        $simulation = Simulation::find($id);
        $simulation->status = $status;
        $simulation->save();

        return $simulation;
    }

    /* To be added if neccessary... */
    /*
    private function applyFilters(\Illuminate\Database\Eloquent\Builder $builder, $filters = null)
    {
        // apply filtering with where(), etc. using Eloquent Builder...

        return $builder;
    }
    */

    /*
    private function applyFilters(Builder $queryBuilder, array $filters)
    {
        if ($queryFilter = Arr::get($filters, 'query', null)) {
            $queryBuilder = $queryBuilder->where('name', 'LIKE', '%' . $queryFilter . '%');
        }

        return $queryBuilder;
    }
    */

    public function store(string $userId, array $storeData)
    {
        $user = $this->users->find($userId);

        $simulation = $user->simulations()->create($storeData);

        return $simulation;
    }
}
