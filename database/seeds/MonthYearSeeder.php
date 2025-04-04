<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Family;
use App\MonthYear;



class MonthYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Family::all() as $family){
            factory(MonthYear::class)
                ->create(
                [
                    "year"=> date("Y"),
                    "month"=> date("m"),
                    "family_id"=> $family->id,
                ]
            );
        }
    }
}
