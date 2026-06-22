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
            ['category_id' => 1, 'name' => 'Wireless Headphones', 'slug' => 'wireless-headphones', 'description' => 'Premium noise-cancelling wireless headphones with 30hr battery life', 'price' => 79.99, 'stock' => 50],
            ['category_id' => 1, 'name' => 'Smart Watch', 'slug' => 'smart-watch', 'description' => 'Fitness tracker with heart rate monitor and GPS', 'price' => 199.99, 'stock' => 30],
            ['category_id' => 1, 'name' => 'Bluetooth Speaker', 'slug' => 'bluetooth-speaker', 'description' => 'Portable waterproof speaker with deep bass', 'price' => 49.99, 'stock' => 75],
            ['category_id' => 1, 'name' => 'USB-C Hub', 'slug' => 'usb-c-hub', 'description' => '7-in-1 USB-C hub with HDMI, USB 3.0, and SD card reader', 'price' => 34.99, 'stock' => 100],
            ['category_id' => 2, 'name' => 'Classic T-Shirt', 'slug' => 'classic-t-shirt', 'description' => '100% cotton crew neck tee available in multiple colors', 'price' => 19.99, 'stock' => 200],
            ['category_id' => 2, 'name' => 'Denim Jacket', 'slug' => 'denim-jacket', 'description' => 'Classic denim jacket with a modern fit', 'price' => 89.99, 'stock' => 40],
            ['category_id' => 2, 'name' => 'Running Shoes', 'slug' => 'running-shoes', 'description' => 'Lightweight cushioned running shoes for daily training', 'price' => 129.99, 'stock' => 60],
            ['category_id' => 3, 'name' => 'Indoor Plant Pot', 'slug' => 'indoor-plant-pot', 'description' => 'Ceramic plant pot with drainage hole, 8 inch', 'price' => 24.99, 'stock' => 80],
            ['category_id' => 3, 'name' => 'Scented Candle Set', 'slug' => 'scented-candle-set', 'description' => 'Set of 3 soy wax candles with lavender, vanilla, and eucalyptus', 'price' => 29.99, 'stock' => 65],
            ['category_id' => 3, 'name' => 'Throw Blanket', 'slug' => 'throw-blanket', 'description' => 'Soft microfiber throw blanket, 50x60 inches', 'price' => 34.99, 'stock' => 45],
            ['category_id' => 4, 'name' => 'The Art of Coding', 'slug' => 'the-art-of-coding', 'description' => 'A comprehensive guide to software development best practices', 'price' => 39.99, 'stock' => 90],
            ['category_id' => 4, 'name' => 'Mystery at Midnight', 'slug' => 'mystery-at-midnight', 'description' => 'A gripping bestselling mystery novel', 'price' => 14.99, 'stock' => 120],
            ['category_id' => 5, 'name' => 'Yoga Mat', 'slug' => 'yoga-mat', 'description' => 'Non-slip exercise mat, 6mm thick with carrying strap', 'price' => 29.99, 'stock' => 55],
            ['category_id' => 5, 'name' => 'Resistance Bands Set', 'slug' => 'resistance-bands-set', 'description' => 'Set of 5 resistance bands with different intensity levels', 'price' => 19.99, 'stock' => 70],
            ['category_id' => 5, 'name' => 'Water Bottle', 'slug' => 'water-bottle', 'description' => 'Insulated stainless steel water bottle, 32oz', 'price' => 24.99, 'stock' => 150],
        ];

        foreach ($products as $prod) {
            Product::create($prod);
        }
    }
}
