<?php

use App\Models\Business;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create([
            'name' => 'বস্তা',
            'code' => '0001',
            'labels' => 'বস্তা/কেজি',
            'relation' => '1/50',
            'description' => '১ বস্তা = ৫০ কেজি',
            'business_id' => Business::first()->id,
        ]);
    }
}
