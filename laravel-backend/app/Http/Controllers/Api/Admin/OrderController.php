<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        if (request()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $orders = Order::with('user', 'items.product')->latest()->get();
        $orders->each(fn($o) => $o->items->each(fn($i) => $i->product?->setAppends(['final_price', 'has_discount', 'discount_status'])));
        return response()->json($orders);
    }

    public function show($id)
    {
        if (request()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order = Order::with('user', 'items.product')->findOrFail($id);
        $order->items->each(fn($i) => $i->product?->setAppends(['final_price', 'has_discount', 'discount_status']));
        return response()->json($order);
    }

    public function updateStatus(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order = Order::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,processing,completed,cancelled']);
        $order->update(['status' => $request->status]);
        $order->load('user', 'items.product');
        $order->items->each(fn($i) => $i->product?->setAppends(['final_price', 'has_discount', 'discount_status']));
        return response()->json($order);
    }
}
