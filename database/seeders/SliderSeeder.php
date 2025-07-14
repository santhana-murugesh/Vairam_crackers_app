<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Slider;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
