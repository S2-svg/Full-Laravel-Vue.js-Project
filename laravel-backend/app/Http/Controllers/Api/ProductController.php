<<<<<<< HEAD
=======
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with('category', 'reviews.user')->findOrFail($id);
        $product->setAppends(['final_price', 'has_discount', 'discount_status']);
        return response()->json($product);
    }
}
>>>>>>> 270228540f02abaf2f4f0faeff3c16802c8a4e67
