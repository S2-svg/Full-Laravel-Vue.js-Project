<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();

        $totalProducts   = $products->count();
        $lowStock        = $products->where('stock', '>', 0)->where('stock', '<=', 5)->count();
        $outOfStock      = $products->where('stock', 0)->count();
        $categoriesCount = Category::count();
        $categories      = Category::all();

        return view('admin.products.index', compact(
            'products', 'totalProducts', 'lowStock', 'outOfStock', 'categoriesCount', 'categories'
        ));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'discount_start_at' => 'nullable|date',
            'discount_end_at' => 'nullable|date|after_or_equal:discount_start_at',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'discount_percent' => (int) $request->input('discount_percent', 0),
            'discount_start_at' => $request->input('discount_start_at'),
            'discount_end_at' => $request->input('discount_end_at'),
            'stock' => $request->stock,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);
        return redirect('/admin/products')->with('success', 'Product created');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'discount_start_at' => 'nullable|date',
            'discount_end_at' => 'nullable|date|after_or_equal:discount_start_at',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'discount_percent' => (int) $request->input('discount_percent', 0),
            'discount_start_at' => $request->input('discount_start_at'),
            'discount_end_at' => $request->input('discount_end_at'),
            'stock' => $request->stock,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect('/admin/products')->with('success', 'Product updated');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect('/admin/products')->with('success', 'Product deleted');
    }
}
