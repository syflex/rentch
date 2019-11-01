<?php

use Illuminate\Database\Seeder;

class ListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('listings')->insert([
            'vendor_id' => 1, 
            'user_id' => 1, 
            'listing_category_id' => 1,
            'listing_type' => 'Shared',
            'state_id' => 1,
            'local_govt_id' => 1,
            'city_id' => 1,
            'title' => 'Shared apartment at gwarimpa',
            'address' => '24, charlie boy street gwarimpa',
            'description' => 'nice house here, all ensuited',
            'room_policy' => 'no smoking',
            'service_option' => 'yes',
            'service_description' => 'security, water fees',
            'baths' => '3',
            'rooms' => '3',
            'pricing_type' => 'monthly',
            'amount' => '300000',
            'featured' => 0,
            'step' => 0,
            'status' => 1,
            'pricing_type' => 'monthly',
        ]);

        DB::table('listings')->insert([
            'vendor_id' => 1, 
            'user_id' => 1, 
            'listing_category_id' => 1,
            'listing_type' => 'Shared',
            'state_id' => 1,
            'local_govt_id' => 1,
            'city_id' => 1,
            'title' => 'Safe apartment at zuba',
            'address' => '24, charlie boy street Zuba',
            'description' => 'nice house here, all ensuited',
            'room_policy' => 'no smoking',
            'service_option' => 'yes',
            'service_description' => 'security, water fees',
            'baths' => '3',
            'rooms' => '3',
            'pricing_type' => 'monthly',
            'amount' => '300000',
            'featured' => 0,
            'step' => 0,
            'status' => 1,
            'pricing_type' => 'monthly',
        ]);
    }
}
