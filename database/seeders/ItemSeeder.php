<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = Brand::pluck('id')->toArray();
        $categories = Category::pluck('id')->toArray();

        $items = [
            'iPhone 15 Pro Max',
            'Galaxy S24 Ultra',
            'Sony WH-1000XM5',
            'Dell XPS 15',
            'MacBook Pro 14',
            'Asus ROG Strix',
            'Lenovo ThinkPad X1',
            'HP Envy 13',
            'Microsoft Surface Pro 9',
            'Xiaomi Redmi Note 13',
            'Huawei MatePad Pro',
            'LG OLED TV',
        ];

        foreach ($items as $item) {
            Item::query()->create([
                'user_id' => fake()->randomElement([1, 2]),
                'code' => 'ITM' . fake()->unique()->numberBetween(1000, 9999),
                'name' => $item,
                'brand_id' => $brands[array_rand($brands)],
                'category_id' => $categories[array_rand($categories)],
                'attachment' => null,
                'status' => fake()->randomElement(['Active', 'Inactive']),
            ]);
        }
    }
}
