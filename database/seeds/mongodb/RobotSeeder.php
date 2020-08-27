<?php

namespace Database\Seeds\MongoDB;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RobotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('robots')->delete();

        factory(\App\Models\MongoDB\Robot\Robot::class, 10)->create()->each(function ($robot) {
            $user = \App\Models\MongoDB\User\User::first();
            $robot->user()->associate($user);
            $robot->save();
        });
    }
}
