<?php

use Illuminate\Database\Seeder;

class AmenitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('amenities')->insert(['name' => 'Stanby Power', 'icon' => '', 'description' => '']);
         DB::table('amenities')->insert(['name' => 'Parking Space', 'icon' => '', 'description' => '']);
         DB::table('amenities')->insert(['name' => 'Wifi', 'icon' => '', 'description' => '']);
         DB::table('amenities')->insert(['name' => 'Constant Water', 'icon' => '', 'description' => '']);
         DB::table('amenities')->insert(['name' => 'Well/Borehole water', 'icon' => '', 'description' => '']);
         DB::table('amenities')->insert(['name' => 'Security', 'icon' => '', 'description' => '']);
         DB::table('amenities')->insert(['name' => 'Water Logged', 'icon' => '', 'description' => '']);
    }
}
