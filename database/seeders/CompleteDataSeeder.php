<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\Gallery;
use App\Models\FeaturedProduct;
use App\Models\BankAccount;
use App\Models\State;
use App\Models\User;

class CompleteDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@vairamcrackers.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Create States
        $states = [
            ['name' => 'Tamil Nadu'],
            ['name' => 'Kerala'],
            ['name' => 'Karnataka'],
            ['name' => 'Andhra Pradesh'],
            ['name' => 'Telangana'],
            ['name' => 'Maharashtra'],
            ['name' => 'Delhi'],
            ['name' => 'Uttar Pradesh'],
            ['name' => 'Gujarat'],
            ['name' => 'Rajasthan'],
        ];

        foreach ($states as $state) {
            State::create($state);
        }

        // Create Bank Accounts
        $bankAccounts = [
            [
                'bank_name' => 'State Bank of India',
                'account_number' => '1234567890',
                'ifsc_code' => 'SBIN0001234',
                'account_holder_name' => 'Vairam Crackers',
                'branch' => 'Sivakasi Main Branch',
            ],
            [
                'bank_name' => 'Indian Bank',
                'account_number' => '0987654321',
                'ifsc_code' => 'IDIB0000987',
                'account_holder_name' => 'Vairam Crackers',
                'branch' => 'Sivakasi Branch',
            ],
        ];

        foreach ($bankAccounts as $account) {
            BankAccount::create($account);
        }

        // Create Categories
        $categories = [
            [
                'category' => 'Ground Crackers',
                'images' => 'categories/ground-crackers.gif',
                'sort' => 1,
            ],
            [
                'category' => 'Aerial Fireworks',
                'images' => 'categories/aerial-fireworks.gif',
                'sort' => 2,
            ],
            [
                'category' => 'Sparklers',
                'images' => 'categories/sparklers.gif',
                'sort' => 3,
            ],
            [
                'category' => 'Flower Pots',
                'images' => 'categories/flower-pots.gif',
                'sort' => 4,
            ],
            [
                'category' => 'Rockets',
                'images' => 'categories/rockets.gif',
                'sort' => 5,
            ],
            [
                'category' => 'Chakkar',
                'images' => 'categories/chakkar.gif',
                'sort' => 6,
            ],
            [
                'category' => 'Fancy Items',
                'images' => 'categories/fancy-items.gif',
                'sort' => 7,
            ],
            [
                'category' => 'Kids Special',
                'images' => 'categories/kids-special.gif',
                'sort' => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Products
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
                'name' => 'Fountain',
                'tamil_name' => 'ஃபவுண்டன்',
                'description' => 'Piece',
                'category_id' => 7,
                'price' => 55,
                'image' => 'products/fountain.jpg',
                'sort' => 2,
            ],
            [
                'name' => 'Roman Candle',
                'tamil_name' => 'ரோமன் கேண்டில்',
                'description' => 'Piece',
                'category_id' => 7,
                'price' => 65,
                'image' => 'products/roman-candle.jpg',
                'sort' => 3,
            ],

            // Kids Special (Category ID: 8)
            [
                'name' => 'Kids Gun',
                'tamil_name' => 'குழந்தைகள் துப்பாக்கி',
                'description' => 'Piece',
                'category_id' => 8,
                'price' => 25,
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
                'name' => 'Match Box',
                'tamil_name' => 'தீப்பெட்டி',
                'description' => 'Box (100 pieces)',
                'category_id' => 8,
                'price' => 40,
                'image' => 'products/match-box.jpg',
                'sort' => 3,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create Sliders
        $sliders = [
            [
                'image' => 'sliders/diwali-celebration.jpg',
                'content' => 'Celebrate Diwali with Premium Quality Fireworks from Sivakasi. Light up your festivities with our authentic and safe crackers.',
                'button_text' => 'Shop Now',
            ],
            [
                'image' => 'sliders/wedding-fireworks.jpg',
                'content' => 'Make your wedding celebration unforgettable with our spectacular fireworks collection. Premium quality guaranteed.',
                'button_text' => 'View Collection',
            ],
            [
                'image' => 'sliders/festival-special.jpg',
                'content' => 'Festival Special Offers! Get up to 25% discount on bulk orders. Trusted by thousands of customers across India.',
                'button_text' => 'Get Offers',
            ],
            [
                'image' => 'sliders/premium-crackers.jpg',
                'content' => 'Authentic Sivakasi Crackers with Traditional Manufacturing. Experience the joy of genuine fireworks.',
                'button_text' => 'Explore Now',
            ]
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }

        // Create Testimonials
        $testimonials = [
            [
                'name' => 'Rajesh Kumar',
                'content' => 'Excellent quality crackers! The Diwali celebration was amazing with Vairam Crackers. Highly recommended!',
                'rating' => 5,
            ],
            [
                'name' => 'Priya Sharma',
                'content' => 'Best fireworks for our wedding reception. Everyone was impressed with the quality and variety.',
                'rating' => 5,
            ],
            [
                'name' => 'Mohan Singh',
                'content' => 'Authentic Sivakasi crackers at reasonable prices. Will definitely order again for next festival.',
                'rating' => 4,
            ],
            [
                'name' => 'Lakshmi Devi',
                'content' => 'Safe and high-quality products. Perfect for family celebrations. Thank you Vairam Crackers!',
                'rating' => 5,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }

        // Create Gallery
        $galleries = [
            [
                'title' => 'Diwali Celebration',
                'image' => 'gallery/diwali-1.jpg',
                'description' => 'Beautiful Diwali celebration with our premium crackers',
            ],
            [
                'title' => 'Wedding Fireworks',
                'image' => 'gallery/wedding-1.jpg',
                'description' => 'Spectacular wedding fireworks display',
            ],
            [
                'title' => 'Festival Special',
                'image' => 'gallery/festival-1.jpg',
                'description' => 'Festival special fireworks collection',
            ],
            [
                'title' => 'Kids Special',
                'image' => 'gallery/kids-1.jpg',
                'description' => 'Safe and fun fireworks for kids',
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::create($gallery);
        }

        // Create Featured Products
        $featuredProducts = [
            [
                'product_id' => 1, // Laxmi Bomb
                'sort' => 1,
                'is_active' => true,
            ],
            [
                'product_id' => 4, // Sky Shot
                'sort' => 2,
                'is_active' => true,
            ],
            [
                'product_id' => 7, // Electric Sparklers
                'sort' => 3,
                'is_active' => true,
            ],
            [
                'product_id' => 10, // Big Flower Pot
                'sort' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($featuredProducts as $featured) {
            FeaturedProduct::create($featured);
        }

        $this->command->info('Complete data seeded successfully!');
        $this->command->info('Admin Email: admin@vairamcrackers.com');
        $this->command->info('Admin Password: password');
    }
} 