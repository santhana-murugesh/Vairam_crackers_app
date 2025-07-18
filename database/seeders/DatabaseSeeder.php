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
            // Essential seeders that should always run
            AdminUserSeeder::class,
            StateSeeder::class,
            BankAccountSeeder::class,
            
            // Content seeders (categories, products, sliders, etc.)
            CategorySeeder::class,
            ProductSeeder::class,
            SliderSeeder::class,
            TestimonialSeeder::class,
            GallerySeeder::class,
            FeaturedProductSeeder::class,
            Assorted::class,
            
            // Optional seeders (uncomment if needed for testing)
            // OrderSeeder::class, // Commented out to avoid test orders
            // OrderItemsSeeder::class,
            // PaymentSeeder::class,
            // AddressSeeder::class,
            // BillItemSeeder::class,
            // BillOrderSeeder::class,
            // ColorSettingsSeeder::class,
            // CouponSeeder::class,
            // CustomerSeeder::class,
            // EnquirySeeder::class,
            // FAQSeeder::class,
        ]);

    }

}
