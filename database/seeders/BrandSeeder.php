<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Apple',
            'Samsung',
            'Sony',
            'LG',
            'Dell',
            'HP',
            'Lenovo',
            'Asus',
            'Acer',
            'Microsoft',
            'Xiaomi',
            'Huawei',
        ];

        foreach ($brands as $brand) {
            Brand::query()->create([
                'user_id' => fake()->randomElement([1, 2]),
                'code' => 'BR' . fake()->unique()->numberBetween(1000, 9999),
                'name' => $brand,
                'status' => fake()->randomElement(['Active', 'Inactive']),
            ]);
        }
    }
}
