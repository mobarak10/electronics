<?php

use App\Models\Admin\Admin;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'Admin',
            'phone' => '01701028220',
            'username' => 'admin',
            'email' => 'admin@shohozsales.com',
            'password' => Hash::make(123456),
            'thumbnail' => '5154112318',
            'status' => true,
        ]);
    }
}
