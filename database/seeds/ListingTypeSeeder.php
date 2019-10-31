<?php

use Illuminate\Database\Seeder;

class ListingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('listing_types')->insert(['name' => 'Shared', 'description' => '']);
         DB::table('listing_types')->insert(['name' => 'Entire', 'description' => '']);
    }
}
