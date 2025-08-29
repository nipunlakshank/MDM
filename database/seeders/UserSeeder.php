<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('Admin@123'),
            'is_admin' => true,
        ]);
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@mail.com',
            'password' => Hash::make('User@123'),
            'is_admin' => false,
        ]);
    }
}
