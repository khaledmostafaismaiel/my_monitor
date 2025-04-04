<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker ;
use Illuminate\Support\Facades\DB ;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FamilySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(MonthYearSeeder::class);
        $this->call(CategorySeeder::class);
    }
}
