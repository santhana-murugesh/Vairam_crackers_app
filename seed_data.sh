#!/bin/bash

echo "=== SEEDING COMPLETE DATA ==="
echo ""

# Check if database is ready
echo "1. Checking database connection..."
php artisan tinker --execute="echo 'Database connection: '; try { DB::connection()->getPdo(); echo 'SUCCESS'; } catch(Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); exit(1); }" 2>/dev/null

if [ $? -ne 0 ]; then
    echo "✗ Database connection failed!"
    exit 1
fi

echo "✓ Database connection successful"
echo ""

# Clear existing data (optional)
echo "2. Clearing existing data..."
read -p "Do you want to clear existing data? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "Clearing existing data..."
    php artisan tinker --execute="
        App\Models\Product::truncate();
        App\Models\Category::truncate();
        App\Models\Slider::truncate();
        App\Models\Testimonial::truncate();
        App\Models\Gallery::truncate();
        App\Models\FeaturedProduct::truncate();
        App\Models\BankAccount::truncate();
        App\Models\State::truncate();
        echo 'Existing data cleared';
    " 2>/dev/null
    echo "✓ Data cleared"
else
    echo "Skipping data clear"
fi

echo ""

# Run the complete seeder
echo "3. Running CompleteDataSeeder..."
php artisan db:seed --class=CompleteDataSeeder

if [ $? -eq 0 ]; then
    echo "✓ CompleteDataSeeder executed successfully!"
else
    echo "✗ CompleteDataSeeder failed!"
    exit 1
fi

echo ""
echo "=== SEEDING COMPLETE ==="
echo ""
echo "Your data has been successfully seeded with:"
echo "✅ Admin User (admin@vairamcrackers.com / password)"
echo "✅ 10 States"
echo "✅ 2 Bank Accounts"
echo "✅ 8 Categories"
echo "✅ 24 Products (3 per category)"
echo "✅ 4 Sliders"
echo "✅ 4 Testimonials"
echo "✅ 4 Gallery Items"
echo "✅ 4 Featured Products"
echo ""
echo "You can now run: php artisan serve"
echo "And visit: http://localhost:8000" 