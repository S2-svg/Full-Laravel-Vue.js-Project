<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Gadgets, devices, and electronic accessories'],
            ['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Fashion apparel for men and women'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Furniture, decor, and gardening tools'],
            ['name' => 'Books', 'slug' => 'books', 'description' => 'Fiction, non-fiction, and educational books'],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports equipment and activewear'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $products = [
            [
                'category_id' => 1,
                'name' => 'Wireless Bluetooth Headphones',
                'slug' => 'wireless-bluetooth-headphones',
                'description' => 'High-quality wireless headphones with noise cancellation and 20-hour battery life.',
                'price' => 79.99,
                'stock' => 50,
            ],
            [
                'category_id' => 1,
                'name' => 'Smart Watch Pro',
                'slug' => 'smart-watch-pro',
                'description' => 'Feature-packed smartwatch with heart rate monitor, GPS, and water resistance.',
                'price' => 199.99,
                'stock' => 30,
            ],
            [
                'category_id' => 1,
                'name' => 'USB-C Fast Charger',
                'slug' => 'usb-c-fast-charger',
                'description' => '65W USB-C charger compatible with laptops, tablets, and phones.',
                'price' => 29.99,
                'stock' => 100,
            ],
            [
                'category_id' => 2,
                'name' => 'Classic Cotton T-Shirt',
                'slug' => 'classic-cotton-t-shirt',
                'description' => 'Comfortable 100% cotton t-shirt available in multiple colors.',
                'price' => 19.99,
                'stock' => 200,
            ],
            [
                'category_id' => 2,
                'name' => 'Slim Fit Jeans',
                'slug' => 'slim-fit-jeans',
                'description' => 'Modern slim fit jeans with stretch denim for all-day comfort.',
                'price' => 49.99,
                'stock' => 80,
            ],
            [
                'category_id' => 3,
                'name' => 'Indoor Plant Pot Set',
                'slug' => 'indoor-plant-pot-set',
                'description' => 'Set of 3 ceramic plant pots with drainage holes and wooden stands.',
                'price' => 34.99,
                'stock' => 40,
            ],
            [
                'category_id' => 3,
                'name' => 'LED Desk Lamp',
                'slug' => 'led-desk-lamp',
                'description' => 'Adjustable LED desk lamp with 5 brightness levels and USB charging port.',
                'price' => 24.99,
                'stock' => 60,
            ],
            [
                'category_id' => 4,
                'name' => 'The Art of Clean Code',
                'slug' => 'the-art-of-clean-code',
                'description' => 'A comprehensive guide to writing maintainable and efficient software.',
                'price' => 39.99,
                'stock' => 25,
            ],
            [
                'category_id' => 5,
                'name' => 'Yoga Mat Premium',
                'slug' => 'yoga-mat-premium',
                'description' => 'Non-slip yoga mat with carrying strap, 6mm thick for extra comfort.',
                'price' => 29.99,
                'stock' => 70,
            ],
            [
                'category_id' => 5,
                'name' => 'Adjustable Dumbbells Set',
                'slug' => 'adjustable-dumbbells-set',
                'description' => 'Pair of adjustable dumbbells from 5 to 25 lbs, perfect for home workouts.',
                'price' => 149.99,
                'stock' => 15,
            ],
        ];

        foreach ($products as $prod) {
            Product::create($prod);
        }
    }
}
