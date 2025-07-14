<?php

namespace Database\Seeders;

use App\Models\FeaturedProduct;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeaturedProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing featured products
        FeaturedProduct::truncate();

        // Get some products to feature
        $products = Product::inRandomOrder()->limit(8)->get();

        if ($products->count() === 0) {
            $this->command->warn('No products found. Please run ProductSeeder first.');
            return;
        }

        $highlights = [
            'BESTSELLER',
            'LIMITED TIME',
            'NEW ARRIVAL',
            'PREMIUM QUALITY',
            'CUSTOMER FAVORITE',
            'SPECIAL OFFER',
            'TOP RATED',
            'EXCLUSIVE'
        ];

        $backgroundColors = [
            '#8B5CF6', // Purple
            '#10B981', // Green
            '#F59E0B', // Orange
            '#EF4444', // Red
            '#3B82F6', // Blue
            '#6366F1', // Indigo
            '#EC4899', // Pink
            '#84CC16', // Lime
        ];

        $featuredDescriptions = [
            'Experience the finest quality fireworks with spectacular effects and brilliant colors.',
            'Premium crafted crackers that deliver extraordinary visual and sound effects.',
            'Traditional fireworks made with authentic techniques for memorable celebrations.',
            'High-quality crackers that ensure safety while delivering amazing performances.',
            'Professional-grade fireworks perfect for festivals and special occasions.',
            'Eco-friendly crackers that provide stunning displays with minimal environmental impact.',
            'Artistically designed fireworks that create mesmerizing patterns in the sky.',
            'Heavy-duty crackers for grand celebrations and large-scale events.',
        ];

        foreach ($products as $index => $product) {
            FeaturedProduct::create([
                'product_id' => $product->id,
                'title' => null, // Will use product name
                'description' => null,
                'highlight_text' => $highlights[$index] ?? 'FEATURED',
                'featured_description' => $featuredDescriptions[$index] ?? 'Premium quality fireworks for your celebrations.',
                'icon' => null, // Will use product image
                'image' => null,
                'button_text' => 'View Product',
                'button_url' => '/order-now', // Or generate specific product URL
                'sort_order' => $index + 1,
                'background_color' => $backgroundColors[$index] ?? '#8B5CF6',
                'text_color' => '#FFFFFF',
                'is_active' => true,
                'show_price' => true,
                'show_discount' => true,
            ]);
        }

        $this->command->info('Featured products seeded successfully with ' . $products->count() . ' products!');
    }
}
