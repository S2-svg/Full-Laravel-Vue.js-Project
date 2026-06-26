<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('orders')->latest()->get();

        $totalUsers    = $users->count();
        $newThisMonth  = $users->where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        $withOrders    = $users->where('orders_count', '>', 0)->count();
        $adminCount    = $users->where('role', 'admin')->count();

        return view('admin.users.index', compact(
            'users', 'totalUsers', 'newThisMonth', 'withOrders', 'adminCount'
        ));
    }
}
