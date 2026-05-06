<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence(6);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->paragraph(),
            'content' => '<p>' . implode('</p><p>', $this->faker->paragraphs(5)) . '</p>',
            'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=1200',
            'status' => 'published',
            'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'seo_title' => $title,
            'seo_description' => $this->faker->paragraph(),
        ];
    }
}
