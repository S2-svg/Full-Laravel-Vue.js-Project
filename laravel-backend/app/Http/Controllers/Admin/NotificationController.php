<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NotificationController extends Controller
{
    public function unreadCount()
    {
        $count = AdminNotification::unread()->count();
        return response()->json(['count' => $count]);
    }

    public function index(Request $request)
    {
        $query = AdminNotification::with('order', 'product')->latest();

        // Support since_id for polling — only fetch notifications newer than this ID
        if ($request->filled('since_id')) {
            $query->where('id', '>', (int) $request->since_id);
        }

        $notifications = $query->limit(20)->get();
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

    /**
     * Stream new notifications via Server-Sent Events.
     * The client connects via EventSource, the server polls the
     * database every 2 seconds and pushes new notifications as they arrive.
     */
    public function stream(Request $request)
    {
        $lastId = (int) $request->query('last_id', 0);
        $maxDuration = 60;
        $startTime = time();

        $response = new StreamedResponse(function () use ($lastId, $maxDuration, $startTime) {
            // Disable output buffering
            if (ob_get_level()) {
                ob_end_clean();
            }
            ob_implicit_flush(true);

            $lastKnownId = $lastId;

            while (time() - $startTime < $maxDuration) {
                $notifications = AdminNotification::with('order', 'product')
                    ->where('id', '>', $lastKnownId)
                    ->latest()
                    ->get();

                if ($notifications->isNotEmpty()) {
                    foreach ($notifications as $notification) {
                        $data = json_encode([
                            'id'         => $notification->id,
                            'type'       => $notification->type,
                            'message'    => $notification->message,
                            'order_id'   => $notification->order_id,
                            'product_id' => $notification->product_id,
                            'created_at' => $notification->created_at->toISOString(),
                            'read_at'    => $notification->read_at?->toISOString(),
                        ]);
                        echo "event: notification\n";
                        echo "data: {$data}\n\n";
                        flush();

                        if (connection_aborted()) {
                            return;
                        }
                    }
                    $lastKnownId = $notifications->max('id');
                } else {
                    // Keep-alive comment
                    echo ": keepalive\n\n";
                    flush();
                }

                if (connection_aborted()) {
                    return;
                }

                sleep(2);
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }
}
