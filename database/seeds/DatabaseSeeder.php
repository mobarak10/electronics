<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(AdminTableSeeder::class);
         $this->call(BusinessTableSeeder::class);
         $this->call(UserTableSeeder::class);
         $this->call(CashTableSeeder::class);
         $this->call(WarehouseTableSeeder::class);
         $this->call(UnitTableSeeder::class);
         $this->call(CategoryTableSeeder::class);
         $this->call(BrandTableSeeder::class);
    }
}
