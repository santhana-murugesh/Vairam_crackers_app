<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Ensure you import DB

class Assorted extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array of products with name and price
        $products = [
            ['name' => 'Assorted Crackers 1000', 'price' => 1000.00],
            ['name' => 'Assorted Crackers 1500', 'price' => 1500.00],
            ['name' => 'Assorted Crackers 2000', 'price' => 2000.00],
            ['name' => 'Assorted Crackers 2500', 'price' => 2500.00],
            ['name' => 'Assorted Crackers 3000', 'price' => 3000.00],
            ['name' => 'Assorted Crackers 3500', 'price' => 3500.00],
            ['name' => 'Assorted Crackers 4000', 'price' => 4000.00],
            ['name' => 'Assorted Crackers 4500', 'price' => 4500.00],
            ['name' => 'Assorted Crackers 5000', 'price' => 5000.00],
            ['name' => 'Assorted Crackers 5500', 'price' => 5500.00],
            ['name' => 'Assorted Crackers 6000', 'price' => 6000.00],
            ['name' => 'Assorted Crackers 6500', 'price' => 6500.00],
            ['name' => 'Assorted Crackers 7000', 'price' => 7000.00],
            ['name' => 'Assorted Crackers 7500', 'price' => 7500.00],
            ['name' => 'Assorted Crackers 8000', 'price' => 8000.00],
            ['name' => 'Assorted Crackers 8500', 'price' => 8500.00],
            ['name' => 'Assorted Crackers 9000', 'price' => 9000.00],
            ['name' => 'Assorted Crackers 9500', 'price' => 9500.00],
            ['name' => 'Assorted Crackers 10000', 'price' => 10000.00],
        ];

        // Insert the product data into the billing_products table
        foreach ($products as $product) {
            DB::table('billing_products')->insert([
                'name' => $product['name'],
                'price' => $product['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
