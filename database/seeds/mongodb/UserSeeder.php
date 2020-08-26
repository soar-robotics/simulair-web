<?php

namespace Database\Seeds\MongoDB;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        factory(\App\Models\MongoDB\User\User::class, 1)->create()->each(function ($user) {
            $user->email = "test@lubarto.com";
            $user->password = Hash::make("password");
            $user->email_verified_at = Carbon::now();
            $user->save();
        });
        factory(\App\Models\MongoDB\User\User::class, 9)->create()->each(function ($user) {
            $user->save();
        });
    }
}
