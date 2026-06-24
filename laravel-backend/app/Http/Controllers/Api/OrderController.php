<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('items.product')->latest()->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $carts = $request->user()->carts()->with('product')->get();

        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $total = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        $order = Order::create([
            'user_id' => $request->user()->id,
            'order_number' => 'ORD-' . Str::random(10),
            'status' => 'pending',
            'total' => $total,
        ]);

        foreach ($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
            ]);
        }

        Cart::where('user_id', $request->user()->id)->delete();

        // Notify admin about the new order
        AdminNotification::create([
            'type' => 'new_order',
            'message' => 'New order #' . $order->order_number . ' from ' . $request->user()->name,
            'order_id' => $order->id,
        ]);

        return response()->json(Order::with('items.product')->find($order->id), 201);
    }

    public function show(Request $request, $id)
    {
        $order = $request->user()->orders()->with('items.product')->findOrFail($id);
        return response()->json($order);
    }
}
