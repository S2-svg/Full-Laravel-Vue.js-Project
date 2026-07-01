<<<<<<< HEAD
=======
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // Validate initial stock availability
        $outOfStockItems = [];
        foreach ($carts as $cart) {
            $product = $cart->product;
            if (!$product) {
                $outOfStockItems[] = [
                    'name' => 'Unknown product',
                    'available' => 0,
                    'requested' => $cart->quantity,
                ];
                continue;
            }
            if ($product->stock < $cart->quantity) {
                $outOfStockItems[] = [
                    'name' => $product->name,
                    'available' => $product->stock,
                    'requested' => $cart->quantity,
                ];
            }
        }

        if (!empty($outOfStockItems)) {
            $messages = collect($outOfStockItems)->map(function ($item) {
                if ($item['available'] <= 0) {
                    return "{$item['name']} is out of stock";
                }
                return "{$item['name']} only has {$item['available']} in stock (you requested {$item['requested']})";
            })->implode('. ');

            return response()->json([
                'message' => $messages,
                'out_of_stock' => $outOfStockItems,
            ], 400);
        }

        // Wrap everything in a DB transaction with row locking to prevent overselling
        try {
            $order = DB::transaction(function () use ($request, $carts) {
                // Re-load products with row-level lock to prevent race conditions
                $productIds = $carts->pluck('product_id')->unique()->values()->all();
                $lockedProducts = \App\Models\Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                // Re-validate stock under lock
                foreach ($carts as $cart) {
                    $product = $lockedProducts->get($cart->product_id);
                    if (!$product) {
                        throw new \RuntimeException("Product #{$cart->product_id} no longer exists");
                    }
                    if ($product->stock < $cart->quantity) {
                        throw new \RuntimeException(
                            $product->stock <= 0
                                ? "{$product->name} is out of stock"
                                : "{$product->name} only has {$product->stock} in stock (you requested {$cart->quantity})"
                        );
                    }
                }

                $total = collect($carts)->sum(function ($cart) use ($lockedProducts) {
                    $product = $lockedProducts->get($cart->product_id);
                    return $product->final_price * $cart->quantity;
                });

                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'order_number' => 'ORD-' . Str::random(10),
                    'status' => 'pending',
                    'total' => $total,
                ]);

                foreach ($carts as $cart) {
                    $product = $lockedProducts->get($cart->product_id);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cart->product_id,
                        'quantity' => $cart->quantity,
                        'price' => $product->final_price,
                    ]);

                    // Decrement stock (safe within the lock)
                    $product->decrement('stock', $cart->quantity);
                }

                // Check for low-stock products and notify admin
                \App\Models\AdminNotification::checkLowStock();

                Cart::where('user_id', $request->user()->id)->delete();

                // Notify admin about the new order
                AdminNotification::create([
                    'type' => 'new_order',
                    'message' => 'New order #' . $order->order_number . ' from ' . $request->user()->name,
                    'order_id' => $order->id,
                ]);

                return $order;
            });

            $order = Order::with('items.product')->find($order->id);
            return response()->json($order, 201);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function show(Request $request, $id)
    {
        $order = $request->user()->orders()->with('items.product')->findOrFail($id);
        return response()->json($order);
    }

    public function reorder(Request $request, $id)
    {
        $order = $request->user()->orders()->with('items.product')->findOrFail($id);

        if ($order->items->isEmpty()) {
            return response()->json(['message' => 'This order has no items to reorder'], 400);
        }

        $addedCount = 0;

        foreach ($order->items as $item) {
            // Check if product still exists
            if (!$item->product) {
                continue;
            }

            $cart = Cart::where('user_id', $request->user()->id)
                ->where('product_id', $item->product_id)
                ->first();

            if ($cart) {
                // Add to existing cart quantity
                $cart->increment('quantity', $item->quantity);
            } else {
                Cart::create([
                    'user_id' => $request->user()->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ]);
            }

            $addedCount++;
        }

        if ($addedCount === 0) {
            return response()->json(['message' => 'No items could be added to cart — products may no longer be available'], 400);
        }

        return response()->json([
            'message' => "{$addedCount} item(s) added to your cart",
            'added_count' => $addedCount,
        ]);
    }
}
>>>>>>> 270228540f02abaf2f4f0faeff3c16802c8a4e67
