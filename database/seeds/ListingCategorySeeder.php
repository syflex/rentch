<?php

use Illuminate\Database\Seeder;

class ListingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('listing_categories')->insert(['name' => 'Bungalow', 'description' => '']);
         DB::table('listing_categories')->insert(['name' => 'Flat', 'description' => '']);
         DB::table('listing_categories')->insert(['name' => 'Tenement', 'description' => '']);
         DB::table('listing_categories')->insert(['name' => 'Office Space', 'description' => '']);
         DB::table('listing_categories')->insert(['name' => 'Warehouse', 'description' => '']);
        
    }
}
