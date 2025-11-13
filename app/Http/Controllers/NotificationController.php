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
     * Get notifications for dropdown (returns JSON)
     */
    public function index()
    {
        $user = Auth::user();

        // fetch user's notifications
        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        // unread count (support is_read or read_at)
        $unreadCount = $notifications->where('is_read', false)->count()
            ?: $notifications->whereNull('read_at')->count();

        // Return JSON instead of view for the JavaScript to consume
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
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

        // Return JSON for AJAX request
        return response()->json(['success' => true]);
    }

    /**
     * Display all notifications page
     */
    public function viewAll()
    {
        $user = Auth::user();

        // Get all notifications with pagination
        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        // Get unread count
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('notifications.all', compact('notifications', 'unreadCount'));
    }
}
