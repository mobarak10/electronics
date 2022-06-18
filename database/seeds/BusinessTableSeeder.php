<?php

use App\Models\Business;
use Illuminate\Database\Seeder;

class BusinessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Business::create([
            'name' => 'Shohozsales',
            'thumbnail' => '6798326',
            'address' => '5B Green House, 27/2 Ram Babu Road Mymensingh-2200, Bangladesh',
            'phone' => '+880 1786 494650'
        ]);
    }
}
