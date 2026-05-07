<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['title' => 'Toyota', 'slug' => 'toyota'],
            ['title' => 'Lexus', 'slug' => 'lexus'],
            ['title' => 'BMW', 'slug' => 'bmw'],
            ['title' => 'Mercedes-Benz', 'slug' => 'mercedes-benz'],
            ['title' => 'Kia', 'slug' => 'kia'],
            ['title' => 'Hyundai', 'slug' => 'hyundai'],
            ['title' => 'Nissan', 'slug' => 'nissan'],
            ['title' => 'Mitsubishi', 'slug' => 'mitsubishi'],
            ['title' => 'Chevrolet', 'slug' => 'chevrolet'],
            ['title' => 'Volkswagen', 'slug' => 'volkswagen'],
        ];

        foreach ($brands as $brand) {
            \App\Models\Brand::create($brand);
        }
    }
}
