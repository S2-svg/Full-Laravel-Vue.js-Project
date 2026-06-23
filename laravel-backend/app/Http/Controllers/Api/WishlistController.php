<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role === 'admin') {
            return response()->json(['message' => 'Admins cannot access wishlist'], 403);
        }

        $wishlists = $request->user()->wishlists()->with('product')->get();
        return response()->json($wishlists);
    }

    public function store(Request $request)
    {
        if ($request->user()->role === 'admin') {
            return response()->json(['message' => 'Admins cannot modify wishlist'], 403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json($wishlist, 201);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->role === 'admin') {
            return response()->json(['message' => 'Admins cannot modify wishlist'], 403);
        }

        $wishlist = $request->user()->wishlists()->findOrFail($id);
        $wishlist->delete();

        return response()->json(['message' => 'Removed from wishlist']);
    }
}
