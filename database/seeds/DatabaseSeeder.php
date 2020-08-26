<?php

use Database\Seeds\MongoDB\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(Database\Seeds\MongoDB\RobotSeeder::class);
        $this->call(Database\Seeds\MongoDB\EnvironmentSeeder::class);
        $this->call(Database\Seeds\MongoDB\SimulationSeeder::class);
    }
}
