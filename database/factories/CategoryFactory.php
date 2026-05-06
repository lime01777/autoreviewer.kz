<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Официальные дилеры',
            'Автоцентры',
            'Мультибрендовые салоны',
            'Электромобили',
            'Грузовой транспорт',
            'Спецтехника',
            'Мотосалоны',
            'Автомагазины запчастей',
            'Тюнинг ателье',
            'Детейлинг центры'
        ]);

        return [
            'title' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(),
        ];
    }
}
