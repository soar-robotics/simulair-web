<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimulationsCollection extends Database\Migrations\MongoDB\Base\BaseSchemaMigration
{
    public function dbUp()
    {
        $this->schema->create('simulations', function ($collection) {
            $collection->bigIncrements('id');
            $collection->index('user_id');
            $collection->index('robot_id');
            $collection->index('environment_id');
        });
    }

    public function dbDown()
    {
        $this->schema->dropIfExists('simulations');
    }
}
