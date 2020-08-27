<?php

namespace Database\Seeds\MongoDB;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('simulations')->delete();

        factory(\App\Models\MongoDB\Simulation\Simulation::class, 10)->create()->each(function ($simulation) {
            $user = \App\Models\MongoDB\User\User::first();
            $robot = \App\Models\MongoDB\Robot\Robot::take(10)->get()->random();
            $environment = \App\Models\MongoDB\Environment\Environment::take(10)->get()->random();
            $simulation->user()->associate($user);
            $simulation->robot()->associate($robot);
            $simulation->environment()->associate($environment);
            $simulation->save();
        });
    }
}
