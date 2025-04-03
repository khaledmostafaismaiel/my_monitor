<?php

use Illuminate\Database\Seeder;

use App\Family;

class FamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Family::class)->create();
    }
}
