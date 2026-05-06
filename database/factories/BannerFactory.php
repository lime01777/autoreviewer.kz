<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'image' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&q=80&w=1200',
            'link' => '#',
            'position' => $this->faker->randomElement([
                'main_top', 'main_sidebar', 'dealership_top', 
                'dealership_sidebar', 'catalog_top', 'footer'
            ]),
            'status' => 'active',
        ];
    }
}
