<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Ground Crackers (Category ID: 1)
            [
                'name' => 'Laxmi Bomb',
                'tamil_name' => 'லட்சுமி பாம்',
                'description' => 'Box',
                'category_id' => 1,
                'price' => 300,
                'image' => 'products/laxmi-bomb.jpg',
                'sort' => 1,
            ],
            [
                'name' => 'Atom Bomb',
                'tamil_name' => 'அணு குண்டு',
                'description' => 'Box',
                'category_id' => 1,
                'price' => 250,
                'image' => 'products/atom-bomb.jpg',
                'sort' => 2,
            ],
            [
                'name' => 'King of King',
                'tamil_name' => 'கிங் ஆஃப் கிங்',
                'description' => 'Box',
                'category_id' => 1,
                'price' => 400,
                'image' => 'products/king-of-king.jpg',
                'sort' => 3,
            ],

            // Aerial Fireworks (Category ID: 2)
            [
                'name' => 'Sky Shot',
                'tamil_name' => 'ஸ்கை ஷாட்',
                'description' => 'Piece',
                'category_id' => 2,
                'price' => 150,
                'image' => 'products/sky-shot.jpg',
                'sort' => 1,
            ],
            [
                'name' => 'Color Koti',
                'tamil_name' => 'கலர் கோட்டி',
                'description' => 'Piece',
                'category_id' => 2,
                'price' => 180,
                'image' => 'products/color-koti.jpg',
                'sort' => 2,
            ],
            [
                'name' => '30 Shot',
                'tamil_name' => '30 ஷாட்',
                'description' => 'Piece',
                'category_id' => 2,
                'price' => 200,
                'image' => 'products/30-shot.jpg',
                'sort' => 3,
            ],

            // Sparklers (Category ID: 3)
            [
                'name' => 'Electric Sparklers',
                'tamil_name' => 'எலெக்ட்ரிக் ஸ்பார்க்லர்',
                'description' => 'Box (50 pieces)',
                'category_id' => 3,
                'price' => 80,
                'image' => 'products/electric-sparklers.jpg',
                'sort' => 1,
            ],
            [
                'name' => 'Color Sparklers',
                'tamil_name' => 'கலர் ஸ்பார்க்லர்',
                'description' => 'Box (25 pieces)',
                'category_id' => 3,
                'price' => 120,
                'image' => 'products/color-sparklers.jpg',
                'sort' => 2,
            ],
            [
                'name' => 'Gold Sparklers',
                'tamil_name' => 'கோல்டு ஸ்பார்க்லர்',
                'description' => 'Box (10 pieces)',
                'category_id' => 3,
                'price' => 100,
                'image' => 'products/gold-sparklers.jpg',
                'sort' => 3,
            ],

            // Flower Pots (Category ID: 4)
            [
                'name' => 'Big Flower Pot',
                'tamil_name' => 'பெரிய மலர் பானை',
                'description' => 'Piece',
                'category_id' => 4,
                'price' => 60,
                'image' => 'products/big-flower-pot.jpg',
                'sort' => 1,
            ],
            [
                'name' => 'Color Flower Pot',
                'tamil_name' => 'கலர் மலர் பானை',
                'description' => 'Piece',
                'category_id' => 4,
                'price' => 75,
                'image' => 'products/color-flower-pot.jpg',
                'sort' => 2,
            ],
            [
                'name' => 'Special Flower Pot',
                'tamil_name' => 'ஸ்பெஷல் மலர் பானை',
                'description' => 'Piece',
                'category_id' => 4,
                'price' => 90,
                'image' => 'products/special-flower-pot.jpg',
                'sort' => 3,
            ],

            // Rockets (Category ID: 5)
            [
                'name' => 'Baby Rocket',
                'tamil_name' => 'பேபி ராக்கெட்',
                'description' => 'Box (10 pieces)',
                'category_id' => 5,
                'price' => 120,
                'image' => 'products/baby-rocket.jpg',
                'sort' => 1,
            ],
            [
                'name' => 'Big Rocket',
                'tamil_name' => 'பெரிய ராக்கெட்',
                'description' => 'Box (5 pieces)',
                'category_id' => 5,
                'price' => 180,
                'image' => 'products/big-rocket.jpg',
                'sort' => 2,
            ],
            [
                'name' => 'Whistling Rocket',
                'tamil_name' => 'விசில் ராக்கெட்',
                'description' => 'Box (10 pieces)',
                'category_id' => 5,
                'price' => 150,
                'image' => 'products/whistling-rocket.jpg',
                'sort' => 3,
            ],

            // Chakkar (Category ID: 6)
            [
                'name' => 'Ground Chakkar',
                'tamil_name' => 'கிரவுண்டு சக்கர',
                'description' => 'Box (10 pieces)',
                'category_id' => 6,
                'price' => 100,
                'image' => 'products/ground-chakkar.jpg',
                'sort' => 1,
            ],
            [
                'name' => 'Big Chakkar',
                'tamil_name' => 'பெரிய சக்கர',
                'description' => 'Box (5 pieces)',
                'category_id' => 6,
                'price' => 140,
                'image' => 'products/big-chakkar.jpg',
                'sort' => 2,
            ],
            [
                'name' => 'Deluxe Chakkar',
                'tamil_name' => 'டீலக்ஸ் சக்கர',
                'description' => 'Box (10 pieces)',
                'category_id' => 6,
                'price' => 160,
                'image' => 'products/deluxe-chakkar.jpg',
                'sort' => 3,
            ],

            // Fancy Items (Category ID: 7)
            [
                'name' => 'Butterfly',
                'tamil_name' => 'பட்டாம்பூச்சி',
                'description' => 'Piece',
                'category_id' => 7,
                'price' => 45,
                'image' => 'products/butterfly.jpg',
                'sort' => 1,
            ],
            [
                'name' => 'Peacock',
                'tamil_name' => 'மயில்',
                'description' => 'Piece',
                'category_id' => 7,
                'price' => 55,
                'image' => 'products/peacock.jpg',
                'sort' => 2,
            ],
            [
                'name' => 'Dancing Doll',
                'tamil_name' => 'நடன பொம்மை',
                'description' => 'Piece',
                'category_id' => 7,
                'price' => 65,
                'image' => 'products/dancing-doll.jpg',
                'sort' => 3,
            ],

            // Kids Special (Category ID: 8)
            [
                'name' => 'Kids Gun',
                'tamil_name' => 'குழந்தைகள் துப்பாக்கி',
                'description' => 'Box (10 pieces)',
                'category_id' => 8,
                'price' => 80,
                'image' => 'products/kids-gun.jpg',
                'sort' => 1,
            ],
            [
                'name' => 'Pop Pop',
                'tamil_name' => 'பாப் பாப்',
                'description' => 'Box (50 pieces)',
                'category_id' => 8,
                'price' => 30,
                'image' => 'products/pop-pop.jpg',
                'sort' => 2,
            ],
            [
                'name' => 'Snake Tablet',
                'tamil_name' => 'பாம்பு மாத்திரை',
                'description' => 'Box (10 pieces)',
                'category_id' => 8,
                'price' => 25,
                'image' => 'products/snake-tablet.jpg',
                'sort' => 3,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
