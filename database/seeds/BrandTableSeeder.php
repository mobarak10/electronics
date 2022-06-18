<?php

use App\Models\Brand;
use App\Models\Business;
use Illuminate\Database\Seeder;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::create([
            'code' => 'BRA000001',
            'name' => 'Test Brand',
            'slug' => 'test-brand',
            'active' => 1,
            'business_id' => Business::first()->id,
        ]);
    }
}
