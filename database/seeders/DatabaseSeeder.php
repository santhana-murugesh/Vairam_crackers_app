<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            SliderSeeder::class,
            TestimonialSeeder::class,
            GallerySeeder::class,
            BankAccountSeeder::class,
            FeaturedProductSeeder::class,
            StateSeeder::class,
            Assorted::class,
            // OrderSeeder::class, // Commented out to avoid test orders
        ]);

    }

}
