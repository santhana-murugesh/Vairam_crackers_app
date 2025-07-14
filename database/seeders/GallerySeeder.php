<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleries = [
            ['image' => 'gallery/fireworks-display-1.jpg'],
            ['image' => 'gallery/crackers-collection-1.jpg'],
            ['image' => 'gallery/diwali-celebration-1.jpg'],
            ['image' => 'gallery/sparklers-display.jpg'],
            ['image' => 'gallery/aerial-fireworks.jpg'],
            ['image' => 'gallery/ground-crackers.jpg'],
            ['image' => 'gallery/flower-pots.jpg'],
            ['image' => 'gallery/rockets-display.jpg'],
            ['image' => 'gallery/chakkar-collection.jpg'],
            ['image' => 'gallery/fountain-fireworks.jpg'],
            ['image' => 'gallery/wedding-fireworks.jpg'],
            ['image' => 'gallery/festival-celebration.jpg'],
        ];

        foreach ($galleries as $gallery) {
            Gallery::create($gallery);
        }
    }
}
