<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MongoDB\Environment\Environment;
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

$factory->define(Environment::class, function (Faker $faker) {
    return [
        'thumbnail' => $faker->imageUrl(100, 100),
        'name' => 'Environment ' . $faker->firstName,
        'description' => $faker->text(150),
        'access_level' => Environment::ACCESS_LEVEL_PRIVATE,
        'user_id' => null,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
