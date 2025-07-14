<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
