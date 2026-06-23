<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role === 'admin') {
            return response()->json(['message' => 'Admins cannot access cart'], 403);
        }

        $carts = $request->user()->carts()->with('product')->get();
        return response()->json($carts);
    }

    public function store(Request $request)
    {
        if ($request->user()->role === 'admin') {
            return response()->json(['message' => 'Admins cannot add to cart'], 403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $request->quantity,
            ]
        );

        return response()->json($cart, 201);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->role === 'admin') {
            return response()->json(['message' => 'Admins cannot modify cart'], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $request->user()->carts()->findOrFail($id);
        $cart->update(['quantity' => $request->quantity]);

        return response()->json($cart);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->role === 'admin') {
            return response()->json(['message' => 'Admins cannot modify cart'], 403);
        }

        $cart = $request->user()->carts()->findOrFail($id);
        $cart->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }
}
