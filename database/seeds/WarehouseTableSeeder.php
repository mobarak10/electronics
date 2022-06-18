<?php

use App\Models\Business;
use App\Models\User\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::create([
            'code' => 'WAH0001',
            'title' => 'Maxsop Warehouse',
            'address' => '5B Green House, 27/2 Ram Babu Road Mymensingh-2200, Bangladesh',
            'user_id' => User::first()->id,
            'status' => 1,
            'business_id' => Business::first()->id,
        ]);
    }
}
