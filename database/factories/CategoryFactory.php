<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $this->faker->unique()->word(),
        'status' => $this->faker->randomElement(['active', 'inactive']),
        'family_id' => $this->faker->uuid(),
    ];
});
