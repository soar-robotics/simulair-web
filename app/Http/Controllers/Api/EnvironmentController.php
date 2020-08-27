<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MongoDB\User\User;
use App\Services\EnvironmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Resources\Environment\EnvironmentCollection;

class EnvironmentController extends Controller
{
    protected EnvironmentService $environmentService;

    public function __construct(EnvironmentService $environmentService)
    {
        $this->middleware('auth:api');

        $this->environmentService = $environmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return EnvironmentCollection
     */
    public function index(Request $request)
    {
        $userId = auth()->user()->id; // temporary

        $filters = [];
        if ($queryFilter = $request->query('query')) {
            $filters = Arr::add($filters, 'query', $queryFilter);
        }

        $environments = $this->environmentService->getEnvironmentsForUser($userId, $filters);

        return new EnvironmentCollection($environments);
    }
}
