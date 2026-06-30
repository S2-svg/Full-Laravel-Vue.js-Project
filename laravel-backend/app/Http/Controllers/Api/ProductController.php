<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $perPage = $request->per_page ?? 12;
        $products = $query->paginate($perPage);
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::with('category', 'reviews.user')->findOrFail($id);
        return new ProductResource($product);
    }
}
