<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Family;
use App\MonthYear;
use Carbon\Carbon; // Make sure to use Carbon for date manipulation

class MonthYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Family::all() as $family) {
            $currentDate = Carbon::now(); // Get the current date and time
        
            // Loop through 100 years (1200 months)
            for ($i = 0; $i <= 1200; $i++) {
                // Calculate the year and month for each iteration
                $date = $currentDate->copy()->addMonths($i); // Adds $i months to the current date
                
                // Create the record for each month-year combination
                factory(MonthYear::class)->create([
                    "year" => $date->year,
                    "month" => str_pad($date->month, 2, '0', STR_PAD_LEFT),
                    "family_id" => $family->id,
                ]);
            }
        }
    }
}
