<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\MongoDB\Environment\Environment;
use App\Models\MongoDB\Robot\Robot;
use App\Models\MongoDB\Simulation\Simulation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SandboxSeedDataForUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        if (config('app.sandbox')) {
            $user = $event->user;

            factory(Robot::class, 6)->create()->each(function($robot) use($user) {
                $robot->user()->associate($user);
                $robot->save();
            });

            factory(Environment::class, 6)->create()->each(function($environment) use($user) {
                $environment->user()->associate($user);
                $environment->save();
            });
            factory(Simulation::class, 6)->create()->each(function($simulation) use($user) {
                $robot = Robot::where('user_id', $user->id)->take(6)->get()->random();
                $environment = Environment::take(6)->get()->random();
                $simulation->user()->associate($user);
                $simulation->robot()->associate($robot);
                $simulation->environment()->associate($environment);
                $simulation->save();
            });
        }
    }
}
