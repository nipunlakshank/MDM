<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Smartphones',
            'Laptops',
            'Tablets',
            'Televisions',
            'Headphones',
            'Cameras',
            'Monitors',
            'Printers',
            'Smartwatches',
            'Gaming Consoles',
            'Networking Devices',
            'Storage Devices',
        ];

        foreach ($categories as $category) {
            Category::query()->create([
                'user_id' => fake()->randomElement([1, 2]),
                'code' => 'CAT' . fake()->unique()->numberBetween(1000, 9999),
                'name' => $category,
                'status' => fake()->randomElement(['Active', 'Inactive']),
            ]);
        }
    }
}
