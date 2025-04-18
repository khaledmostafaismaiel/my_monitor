<?php

namespace Database\Factories;

use App\Models\MonthYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonthYearFactory extends Factory
{
    protected $model = MonthYear::class;

    public function definition(): array
    {
        return [
            'year' => $this->faker->year,
            'month' => str_pad($this->faker->month, 2, '0', STR_PAD_LEFT),
            'family_id' => $this->faker->uuid,
        ];
    }
}
