<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Simulation\SetSimulationStatusRequest;
use App\Http\Requests\Simulation\StoreSimulation;
use App\Http\Requests\Simulation\StoreSimulationRequest;
use App\Http\Resources\Simulation\SimulationCollection;
use App\Http\Resources\Simulation\SimulationResource;
use App\Models\MongoDB\Simulation\Simulation;
use App\Services\SimulationService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SimulationController extends Controller
{
    protected SimulationService $simulationService;

    public function __construct(SimulationService $simulationService)
    {
        $this->middleware('auth:api');

        $this->simulationService = $simulationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return SimulationCollection
     */
    public function index(Request $request)
    {
        $userId = auth()->user()->id;

        $filters = [];

        if ($queryFilter = $request->query('query')) {
            $filters = Arr::add($filters, 'query', $queryFilter);
        }
        if ($statusFilter = $request->query('status')) {
            $filters = Arr::add($filters, 'status', $statusFilter);
        }

        $simulations = $this->simulationService->getSimulationsForUser($userId, $filters);

        //return new RobotCollection($robots->paginate(2)->appends(["sort" => "test"]));
        return new SimulationCollection($simulations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSimulationRequest $request
     * @return SimulationResource
     */
    public function store(StoreSimulationRequest $request)
    {
        $user = auth()->user();

        $storeData = Arr::only($request->input(), [
            'robot_id', 'environment', 'name', 'description', 'status', 'thumbnail'
        ]);

        $simulation = $this->simulationService->store($user->id, $storeData);

        // later: create response classes to generalise them and make more consistent/reusable (msg/code per response)
        return (new SimulationResource($simulation))->additional([
            'meta' => [
                'message' => 'Simulation created.'
            ]
        ]);
    }

    /**
     * Set status of a Simulation to any of available values.
     *
     * @param SetSimulationStatusRequest $request
     * @param string $id
     * @param string $status
     * @return SimulationResource|\Illuminate\Http\JsonResponse
     */
    public function setStatus(SetSimulationStatusRequest $request, string $id, string $status)
    {
        if (!in_array($status, Simulation::availableStatuses())) {
            return response()->json(['message' => 'The selected status is invalid.'], 422);
        }

        $simulation = $this->simulationService->setStatus($id, $status);

        return (new SimulationResource($simulation))->additional([
            'meta' => [
                'message' => 'Simulation status set.'
            ]
        ]);
    }
}
