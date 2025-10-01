<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'merchant',
            'wallet' => 1000.00
        ]);

        Category::create([
            'name' => 'phone',
            'description' => 'All kinds of phones'
        ]);
        Category::create([
            'name' => 'electronics',
            'description' => 'All kinds of electronics'
        ]);

        Product::create([
            'name' => 'iPhone 13',
            'description' => 'Latest Apple iPhone',
            'price' => 999.99,
            'stock' => 100,
            'category_id' => 1,
            'merchant_id' => 1
        ]);
        Product::create([
            'name' => 'Samsung Galaxy S21',
            'description' => 'Latest Samsung Phone',
            'price' => 899.99,
            'stock' => 50,
            'category_id' => 1,
            'merchant_id' => 1,
        ]);
    }
}
