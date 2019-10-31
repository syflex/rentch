<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert(['state_id' => 15, 'name'=> 'Gwarimpa']);
        DB::table('cities')->insert(['state_id' => 15, 'name'=> 'Jabi']);
        DB::table('cities')->insert(['state_id' => 15, 'name'=> 'Apo']);
        DB::table('cities')->insert(['state_id' => 15, 'name'=> 'Nyanya']);
        DB::table('cities')->insert(['state_id' => 15, 'name'=> 'Kubwa']);
        DB::table('cities')->insert(['state_id' => 15, 'name'=> 'Zuba']);
        DB::table('cities')->insert(['state_id' => 15, 'name'=> 'Bwari']);
        DB::table('cities')->insert(['state_id' => 15, 'name'=> 'Lugbe']);
        
    }
}
