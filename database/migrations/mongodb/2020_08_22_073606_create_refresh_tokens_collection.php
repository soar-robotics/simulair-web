<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefreshTokensCollection extends \Database\Migrations\MongoDB\Base\BaseSchemaMigration
{
    public function dbUp()
    {
        $this->schema->create('refresh_tokens', function ($collection) {
            $collection->bigIncrements('id');
            $collection->index('user_id');
        });
    }

    public function dbDown()
    {
        // TODO: Implement dbDown() method.
    }
}
