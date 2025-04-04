<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MonthYear;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(MonthYear::class, function (Faker $faker) {
    return [
        'year' => $this->faker->year(),
        'month' => str_pad($this->faker->month(), 2, '0', STR_PAD_LEFT),
        'family_id' => $this->faker->uuid(),
    ];
});
