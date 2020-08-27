<?php

namespace App\Http\Resources\Simulation;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SimulationCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Simulation\SimulationResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
