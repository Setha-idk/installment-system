<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Image; // Import Image
use App\Models\Plan;   // Import Plan
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // Import Str for UUID

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. CREATE USER (MERCHANT)
        $merchant = User::factory()->create([
            'name' => 'Test Merchant',
            'email' => 'merchant@example.com', // Using a new email to avoid previous conflict
            'password' => bcrypt('password'),
            'role' => 'merchant',
            'wallet' => 5000.00
        ]);

        // 2. CREATE CATEGORIES
        $categoryPhone = Category::create([
            'name' => 'phone',
            'description' => 'All kinds of phones'
        ]);
        $categoryElectronics = Category::create([
            'name' => 'electronics',
            'description' => 'All kinds of electronics'
        ]);

        // 3. CREATE IMAGES (REQUIRED to link to products)
        $image1 = Image::create(['path' => 'product_images/iphone-13.jpg']);
        $image2 = Image::create(['path' => 'product_images/galaxy-s21.jpg']);
        
        // 4. CREATE PRODUCTS (NOW WITH UUID AND IMAGE/CATEGORY IDs)
        $product1 = Product::create([
            'uuid' => (string) Str::uuid(), // *** FIX: Generate UUID ***
            'name' => 'iPhone 13',
            'description' => 'Latest Apple iPhone with A15 Bionic chip.',
            'price' => 999.99,
            'stock' => 100,
            'category_id' => $categoryPhone->id,
            'merchant_id' => $merchant->id,
            'image_id' => $image1->id, // *** FIX: Link Image ID ***
        ]);

        $product2 = Product::create([
            'uuid' => (string) Str::uuid(), // *** FIX: Generate UUID ***
            'name' => 'Samsung Galaxy S21',
            'description' => 'Flagship Android phone with stunning camera.',
            'price' => 899.99,
            'stock' => 50,
            'category_id' => $categoryPhone->id,
            'merchant_id' => $merchant->id,
            'image_id' => $image2->id, // *** FIX: Link Image ID ***
        ]);
        
        // 5. CREATE INSTALLMENT PLANS (REQUIRED for plans list)
        Plan::create([
            'product_id' => $product1->id,
            'merchant_id' => $merchant->id,
            'name' => 'Standard 6-Month Plan',
            'description' => 'Pay off your iPhone over 6 months.',
            'installments_count' => 6,
            'interest_rate' => 5.50,
        ]);

        Plan::create([
            'product_id' => $product1->id,
            'merchant_id' => $merchant->id,
            'name' => 'Quick Pay 3-Month Plan (No Interest)',
            'description' => 'A short-term plan with 0% interest!',
            'installments_count' => 3,
            'interest_rate' => 0.00, 
        ]);

        // Add plans for Samsung Galaxy S21
        Plan::create([
            'product_id' => $product2->id,
            'merchant_id' => $merchant->id,
            'name' => 'Galaxy Flex 12-Month Plan',
            'description' => 'Spread your payments over a full year',
            'installments_count' => 12,
            'interest_rate' => 7.50,
        ]);

        Plan::create([
            'product_id' => $product2->id,
            'merchant_id' => $merchant->id,
            'name' => 'Galaxy Quick 3-Month Plan',
            'description' => 'Short-term financing with minimal interest',
            'installments_count' => 3,
            'interest_rate' => 2.50,
        ]);
    }
}