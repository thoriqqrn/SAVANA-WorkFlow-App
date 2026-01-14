<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * List all notifications for current user
     */
    public function index()
    {
        $notifications = Notification::forUser(auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread count (AJAX)
     */
    public function unreadCount()
    {
        $count = Notification::forUser(auth()->id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (AJAX for dropdown)
     */
    public function recent()
    {
        $notifications = Notification::forUser(auth()->id())
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $unreadCount = Notification::forUser(auth()->id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure user owns this notification
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        // Redirect based on notification type
        $redirect = match($notification->type) {
            Notification::TYPE_TASK_ASSIGNED, 
            Notification::TYPE_DEADLINE_REMINDER => route('tasks.show', $notification->data['task_id'] ?? 0),
            Notification::TYPE_EVALUATION_NEW => route('evaluations.my'),
            Notification::TYPE_ANNOUNCEMENT => route('announcements.index'),
            default => route('notifications.index'),
        };

        return redirect($redirect);
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notifikasi dihapus');
    }
}
