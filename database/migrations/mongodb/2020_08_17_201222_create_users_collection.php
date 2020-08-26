<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersCollection extends Database\Migrations\MongoDB\Base\BaseSchemaMigration
{
    public function dbUp()
    {
        $this->schema->create('users', function ($collection) {
            $collection->bigIncrements('id');
            $collection->unique('username');
            $collection->unique('email');
        });
    }

    public function dbDown()
    {
        $this->schema->dropIfExists('users');
    }
}
