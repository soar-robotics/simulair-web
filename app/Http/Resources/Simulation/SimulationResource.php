<?php

namespace App\Http\Resources\Simulation;

use App\Http\Resources\Environment\EnvironmentResource;
use App\Http\Resources\Robot\RobotResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;

class SimulationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'status' => $this->status,
            'access_level' => $this->access_level,
            'user' => new UserResource($this->whenLoaded('user')),
            'environment' => new EnvironmentResource($this->whenLoaded('environment')),
            'robot' => new RobotResource($this->whenLoaded('robot')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
