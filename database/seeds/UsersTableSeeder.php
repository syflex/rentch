<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'state_id' => 1, 
            'local_govt_id' => 1, 
            'city_id' => 1, 
            'role' => 'superadmin', 
            'name' => 'John Doe', 
            'phone_number' => 'phone_number', 
            'avatar' => '', 
            'monthly_budget' => '10000', 
            'email' => 'johndoe@gmail.com', 
            'password' => bcrypt('root123'), 
            'current_address' => 'john doe street', 
            'marital_status' => 'Single',
            'gender' => 'Male',
            'age_range' => '16-25',
            'occupation' => 'Self Employed',
            'description' => '',
            'profile_open_status' => 0,
            'email_verified' => 0,
            'is_profile_complete' => 0,
            'title' => 'Mr'
        ]);
        DB::table('users')->insert([
            'state_id' => 1, 
            'local_govt_id' => 1, 
            'city_id' => 1, 
            'role' => 'superadmin', 
            'name' => 'John Doe', 
            'phone_number' => 'phone_number', 
            'avatar' => '', 
            'monthly_budget' => '10000', 
            'email' => 'johndoe1@gmail.com', 
            'password' => bcrypt('root123'), 
            'current_address' => 'john doe street', 
            'marital_status' => 'Single',
            'gender' => 'Male',
            'age_range' => '16-25',
            'occupation' => 'Self Employed',
            'description' => '',
            'profile_open_status' => 0,
            'email_verified' => 0,
            'is_profile_complete' => 0,
            'title' => 'Mr'
        ]);

        DB::table('users')->insert([
            'state_id' => 1, 
            'local_govt_id' => 1, 
            'city_id' => 1, 
            'role' => 'superadmin', 
            'name' => 'user one', 
            'phone_number' => '0706782222', 
            'avatar' => '', 
            'monthly_budget' => '10000', 
            'email' => 'user@gmail.com', 
            'password' => bcrypt('secret'), 
            'current_address' => 'unset long address for user one', 
            'marital_status' => 'Single',
            'gender' => 'Male',
            'age_range' => '16-25',
            'occupation' => 'Self Employed',
            'description' => 'this is about user one',
            'profile_open_status' => 0,
            'email_verified' => 0,
            'is_profile_complete' => 0,
            'title' => 'Mr'
        ]);
        
    }
}
