<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DealershipFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->company() . ' Auto';
        return [
            'title' => $name,
            'slug' => Str::slug($name),
            'short_description' => $this->faker->realText(100),
            'full_description' => $this->faker->realText(1000),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->randomElement(['Алматы', 'Астана', 'Шымкент', 'Караганда', 'Актобе']),
            'phone' => '+7 (700) ' . $this->faker->numerify('###-##-##'),
            'website' => $this->faker->url(),
            'working_hours' => [
                'Пн-Пт' => '09:00 - 19:00',
                'Сб' => '10:00 - 17:00',
                'Вс' => 'Выходной'
            ],
            'logo' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=200',
            'cover_image' => 'https://images.unsplash.com/photo-1562141989-c5c79ac8f576?auto=format&fit=crop&q=80&w=1200',
            'status' => 'published',
            'is_featured' => $this->faker->boolean(20),
            'latitude' => $this->faker->latitude(43.2, 43.3),
            'longitude' => $this->faker->longitude(76.8, 76.9),
        ];
    }
}
