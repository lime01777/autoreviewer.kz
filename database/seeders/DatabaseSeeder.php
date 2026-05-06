<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Dealership;
use App\Models\Review;
use App\Models\News;
use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): array
    {
        // 1. Admin
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@autoreviewer.kz',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Regular Users
        $users = User::factory(5)->create([
            'role' => 'user',
        ]);

        // 3. Categories
        $categories = Category::factory(10)->create();

        // 4. Dealerships
        $dealerships = Dealership::factory(20)->create()->each(function ($dealer) use ($categories, $users) {
            // Attach random categories
            $dealer->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );

            // 5. Reviews for each dealership
            Review::factory(rand(3, 10))->create([
                'dealership_id' => $dealer->id,
                // Assign user_id to some reviews
                'user_id' => function () use ($users) {
                    return rand(0, 1) ? $users->random()->id : null;
                }
            ]);
        });

        // 6. News
        News::factory(10)->create();

        // 7. Banners
        $positions = [
            'main_top', 'main_sidebar', 'dealership_top', 
            'dealership_sidebar', 'catalog_top', 'catalog_sidebar', 
            'news_sidebar', 'footer'
        ];
        
        foreach ($positions as $pos) {
            Banner::factory()->create(['position' => $pos]);
        }

        return [];
    }
}
