<?php

namespace App\Http\Resources\Robot;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RobotCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Robot\RobotResource';

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
