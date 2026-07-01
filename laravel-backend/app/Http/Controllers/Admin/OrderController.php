<<<<<<< HEAD
=======
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders         = Order::with('user', 'items.product')->latest()->paginate(50);
        $totalOrders    = Order::count();
        $pendingCount   = Order::where('status', 'pending')->count();
        $completedCount = Order::where('status', 'completed')->count();
        $cancelledCount = Order::where('status', 'cancelled')->count();
        $totalRevenue   = Order::where('status', 'completed')->sum('total');

        return view('admin.orders.index', compact(
            'orders', 'totalOrders', 'pendingCount', 'completedCount', 'cancelledCount', 'totalRevenue'
        ));
    }

    public function show($id)
    {
        $order = Order::with('user', 'items.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,processing,completed,cancelled']);
        $order->update(['status' => $request->status]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Order status updated']);
        }

        return back()->with('success', 'Order status updated');
    }
}
>>>>>>> 270228540f02abaf2f4f0faeff3c16802c8a4e67
