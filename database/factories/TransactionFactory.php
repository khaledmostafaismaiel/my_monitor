<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Transaction;
use App\Category;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'type' => $this->faker->randomElement(['debit', 'credit']),
        'user_id' => factory(\App\User::class),
        'name' => $this->faker->word(),
        'price' => $this->faker->randomFloat(2, 5, 1000),
        'category_id' => factory(\App\Category::class),
        'comment' => $this->faker->optional()->sentence(),
        'date' => $this->faker->date(),
    ];
});
