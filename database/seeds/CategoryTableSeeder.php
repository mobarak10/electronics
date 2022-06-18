<?php

use App\Models\Business;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'code' => 'CAT000001',
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'N/A',
            'active' => 1,
            'business_id' => Business::first()->id,
        ]);
    }
}
