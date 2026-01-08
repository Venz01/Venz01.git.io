<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display all notifications
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications (for bell dropdown)
     */
    public function getUnread()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->unread()
            ->recent(10)
            ->get();

        $unreadCount = $this->notificationService->getUnreadCount(auth()->id());

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        // Redirect to the URL in the notification data if it exists
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted.',
        ]);
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->read()
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'All read notifications deleted.',
        ]);
    }
}