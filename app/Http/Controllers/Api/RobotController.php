<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MongoDB\User\User;
use App\Http\Resources\Robot\RobotResource;
use App\Http\Resources\Robot\RobotCollection;
use App\Services\RobotService;
use Illuminate\Support\Arr;

class RobotController extends Controller
{
    protected RobotService $robotService;

    public function __construct(RobotService $robotService)
    {
        $this->middleware('auth:api');

        $this->robotService = $robotService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = auth()->user()->id;

        $filters = [];
        if ($queryFilter = $request->query('query')) {
            $filters = Arr::add($filters, 'query', $queryFilter);
        }

        $robots = $this->robotService->getRobotsForUser($userId, $filters);

        //return new RobotCollection($robots->paginate(2)->appends(["sort" => "test"]));
        return new RobotCollection($robots);
    }
}
