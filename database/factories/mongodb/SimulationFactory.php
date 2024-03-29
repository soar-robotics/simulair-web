<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MongoDB\Simulation\Simulation;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Simulation::class, function (Faker $faker) {
    $tempImages = [
        'https://i.imgur.com/q4mv4QN.png',
        'https://i.imgur.com/cUNy0pr.png',
        'https://i.imgur.com/EJth8eS.png',
        'https://i.imgur.com/52gDaGU.png'
    ];

    return [
        'thumbnail' => Arr::random($tempImages),
        'name' => 'Simulation ' . $faker->firstName,
        'status' => Arr::random([
            Simulation::STATUS_RUNNING, Simulation::STATUS_STOPPED
        ]),
        'description' => $faker->text(150),
        'access_level' => Simulation::ACCESS_LEVEL_PRIVATE,
        'user_id' => null,
        'environment_id' => null,
        'robot_id' => null,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
