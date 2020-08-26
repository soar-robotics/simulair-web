<?php

namespace App\Http\Resources\Environment;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EnvironmentCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Environment\EnvironmentResource';

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
