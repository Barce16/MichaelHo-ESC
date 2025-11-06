<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get notifications for dropdown
     */
    public function index()
    {
        $user = Auth::user();

        // fetch user's notifications
        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        // unread count (support is_read or read_at)
        $unreadCount = $notifications->where('is_read', false)->count()
            ?: $notifications->whereNull('read_at')->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }
    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back();
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead(Request $request)
    {
        $this->notificationService->markAllAsRead($request->user());

        return back();
    }
}
