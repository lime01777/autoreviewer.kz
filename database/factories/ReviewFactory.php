<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'author_name' => $this->faker->name(),
            'rating' => $this->faker->numberBetween(1, 5),
            'text' => $this->faker->realText(300),
            'pros' => $this->faker->realText(100),
            'cons' => $this->faker->realText(100),
            'status' => $this->faker->randomElement(['approved', 'pending', 'rejected']),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
        ];
    }
}
