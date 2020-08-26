<?php

namespace Database\Seeds\MongoDB;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EnvironmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('environments')->delete();

        factory(\App\Models\MongoDB\Environment\Environment::class, 10)->create()->each(function ($environment) {
            $user = \App\Models\MongoDB\User\User::first();
            $environment->user()->associate($user);
            $environment->save();
        });
    }
}
