<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Ariful Hoque',
            'email' => 'arif@yopmail.com',
            'password' => bcrypt('12345678'), // Use a secure password
        ]);

        \App\Models\User::factory(10)->create();
        \App\Models\Category::factory(5)->create();
        \App\Models\Product::factory(20)->create();
        \App\Models\Supplier::factory(10)->create();
    }
}
