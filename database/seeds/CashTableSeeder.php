<?php

use App\Models\Cash;
use Illuminate\Database\Seeder;

class CashTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cash::create([
            'title' => 'Main Cash',
            'slug' => 'main-cash',
            'amount' => '100000',
            'business_id' => 1,
        ]);
    }
}
