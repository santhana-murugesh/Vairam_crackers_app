<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Rajesh Kumar',
                'designation' => 'Event Organizer',
                'message' => 'Vairam Crackers provided excellent quality fireworks for our Diwali celebration. The products were authentic and the service was outstanding. Highly recommended!',
                'rating' => 5,
            ],
            [
                'name' => 'Priya Sharma',
                'designation' => 'Customer',
                'message' => 'Amazing collection of crackers with competitive prices. The delivery was prompt and packaging was secure. Will definitely order again next year.',
                'rating' => 5,
            ],
            [
                'name' => 'Arun Patel',
                'designation' => 'Wholesaler',
                'message' => 'I have been ordering from Vairam Crackers for the past 3 years. Their quality consistency and customer service is exceptional. Great business partner.',
                'rating' => 5,
            ],
            [
                'name' => 'Meera Reddy',
                'designation' => 'Festival Coordinator',
                'message' => 'The best crackers from Sivakasi! The sound quality and visual effects were spectacular. Our entire community was delighted with the fireworks display.',
                'rating' => 5,
            ],
            [
                'name' => 'Vikram Singh',
                'designation' => 'Customer',
                'message' => 'Excellent service and premium quality products. The team helped me choose the right crackers for my wedding celebration. Everything went perfectly!',
                'rating' => 4,
            ],
            [
                'name' => 'Kavitha Nair',
                'designation' => 'Event Planner',
                'message' => 'Professional service and high-quality fireworks. The variety of products available is impressive and the pricing is very reasonable.',
                'rating' => 5,
            ],
            [
                'name' => 'Santosh Gupta',
                'designation' => 'Customer',
                'message' => 'Trusted supplier with genuine Sivakasi crackers. The online ordering process is smooth and delivery tracking is excellent.',
                'rating' => 4,
            ],
            [
                'name' => 'Deepika Joshi',
                'designation' => 'Corporate Event Manager',
                'message' => 'The company helped make our corporate Diwali celebration memorable. Professional handling and beautiful fireworks display.',
                'rating' => 5,
            ]
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
