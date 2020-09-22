<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MongoDB\User\User;
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

$factory->define(User::class, function (Faker $faker) {
    $firstName = $faker->firstName();

    return [
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => (Arr::random([0, 1]) == 0) ? null : Carbon::now(),
        'password' => Hash::make("password"),
        'username' => $faker->unique()->passthrough($firstName),
        'first_name' => $firstName,
        'last_name' => $faker->lastName(),
        //'profile_image' => $faker->imageUrl(320, 240),
        'profile_image' => null,
        'company' => $faker->company,
        'google_id' => null,
        'google_avatar' => null,
        'google_token' => null,
        'google_auth_at' => null,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
