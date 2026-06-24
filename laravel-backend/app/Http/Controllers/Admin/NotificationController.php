<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function unreadCount()
    {
        $count = AdminNotification::unread()->count();
        return response()->json(['count' => $count]);
    }

    public function index()
    {
        $notifications = AdminNotification::with('order')
            ->latest()
            ->limit(20)
            ->get();
        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        AdminNotification::unread()->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
}
