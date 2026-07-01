<<<<<<< HEAD
=======
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $carts = $request->user()->carts()->with('product')->get();
        return response()->json($carts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < 1) {
            return response()->json(['message' => "{$product->name} is out of stock"], 400);
        }

        // Check existing cart quantity if this product is already in cart
        $existingCart = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        $totalQuantity = $request->quantity + ($existingCart ? $existingCart->quantity : 0);

        if ($totalQuantity > $product->stock) {
            return response()->json([
                'message' => "Only {$product->stock} of \"{$product->name}\" in stock" .
                    ($existingCart ? " (you already have {$existingCart->quantity} in your cart)" : ""),
            ], 400);
        }

        $cart = Cart::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $totalQuantity,
            ]
        );

        $cart->load('product');
        return response()->json($cart, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $request->user()->carts()->with('product')->findOrFail($id);

        if ($cart->product && $request->quantity > $cart->product->stock) {
            return response()->json([
                'message' => "Only {$cart->product->stock} of \"{$cart->product->name}\" in stock",
            ], 400);
        }

        $cart->update(['quantity' => $request->quantity]);

        return response()->json($cart);
    }

    public function destroy(Request $request, $id)
    {
        $cart = $request->user()->carts()->findOrFail($id);
        $cart->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }
}
>>>>>>> 270228540f02abaf2f4f0faeff3c16802c8a4e67
