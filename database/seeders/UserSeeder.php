<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
            [
             'role_id' => config('constants.status.active'),
             'rank_id' => config('constants.status.inActive'),
             'login' => 'admin',
             'display_name' => "admin",
             'email' => "admin@mypolitics.com",
             'password' => bcrypt('password'),
             'lock_rank' => config('constants.status.inActive'),
             'display_status' => config('constants.status.inActive'),
             'registered_date' => Carbon::now()->format('Y-m-d H:i:s'),
             'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
             'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
