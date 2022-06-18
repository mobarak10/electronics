<?php

use App\Models\User\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'User',
            'phone' => '01701028220',
            'username' => 'user',
            'email' => 'user@shohozsales.com',
            'password' => Hash::make(123456),
            'thumbnail' => '5154112318',
            'business_id' => 1,
            'status' => true,
        ]);
    }
}
