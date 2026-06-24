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
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $products = [
        ];

        foreach ($products as $prod) {
            Product::firstOrCreate(['slug' => $prod['slug']], $prod);
        }
    }
}
