<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(AmenitiesSeeder::class);
        $this->call(ListingCategorySeeder::class);
        $this->call(BlogCategorySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(ListingSeeder::class);
    }
}
