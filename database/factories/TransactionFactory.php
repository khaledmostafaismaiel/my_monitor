<?php

namespace Database\Factories;

use App\Models\NormalTransaction;
use App\Models\User;
use App\Models\Family;
use App\Models\Category;
use App\Models\MonthYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class NormalTransactionFactory extends Factory
{
    protected $model = NormalTransaction::class;

    public function definition(): array
    {
        $date = $this->faker->date();

        return [
            'family_id' => Family::factory(),
            'type' => $this->faker->randomElement(['debit', 'credit']),
            'user_id' => User::factory(),
            'name' => $this->faker->word(),
            'quantity' => $this->faker->randomFloat(2, 5, 1000),
            'price' => $this->faker->randomFloat(2, 5, 1000),
            'category_id' => Category::factory(),
            'comment' => $this->faker->optional()->sentence(),
            'date' => $date,
            'month_year_id' => MonthYear::factory(),
            'is_blueprint' => $this->faker->boolean(),
        ];
    }
}
