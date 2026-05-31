<?php

namespace Database\Factories;

use App\Models\OTP;
use Illuminate\Database\Eloquent\Factories\Factory;

class OtpFactory extends Factory
{
    protected $model = OTP::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->numberBetween(100000, 999999), // 6-digit code
            'phone' => $this->faker->phoneNumber,
            'expires_at' => now()->addMinutes(5),
            'verified' => false,
        ];
    }
}
