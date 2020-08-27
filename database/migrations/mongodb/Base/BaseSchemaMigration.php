<?php

namespace Database\Migrations\MongoDB\Base;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

abstract class BaseSchemaMigration extends Migration
{
    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection("mongodb");
    }

    abstract public function dbUp();

    abstract public function dbDown();

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->dbUp();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dbDown();
    }
}
