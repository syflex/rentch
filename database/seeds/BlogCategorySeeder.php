<?php

use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blog_categories')->insert(['name' => 'Property', 'description' => '']);
        DB::table('blog_categories')->insert(['name' => 'Politics', 'description' => '']);
        DB::table('blog_categories')->insert(['name' => 'Others', 'description' => '']);
        
    }
}
