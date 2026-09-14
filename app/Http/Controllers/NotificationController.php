<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Get all notifications for admin with filters
     */
    public function index(Request $request): View
    {
        $query = AdminNotification::query();

        // Status Filter: all, unread, read
        $status = $request->query('status');
        if ($status === 'unread') {
            $query->unread();
        } elseif ($status === 'read') {
            $query->read();
        }

        // Type Filter: all, new_order, pos_order, customer_registered, order_dispatched, order_delivered, order_cancelled, payment_received
        $type = $request->query('type');
        if (!empty($type) && $type !== 'all') {
            if ($type === 'orders') {
                $query->whereIn('type', ['new_order', 'online_order']);
            } elseif ($type === 'pos') {
                $query->whereIn('type', ['pos_order', 'pos_quotation']);
            } elseif ($type === 'customers') {
                $query->whereIn('type', ['customer_registered', 'new_user_registration']);
            } elseif ($type === 'dispatched') {
                $query->where('type', 'order_dispatched');
            } elseif ($type === 'delivered') {
                $query->where('type', 'order_delivered');
            } elseif ($type === 'cancelled') {
                $query->where('type', 'order_cancelled');
            } elseif ($type === 'payments') {
                $query->whereIn('type', ['payment_received', 'payment_confirmed']);
            } else {
                $query->where('type', $type);
            }
        }

        // Search Filter
        $search = $request->query('search');
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $notifications = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $unreadCount = AdminNotification::unread()->count();
        $readCount = AdminNotification::read()->count();
        $totalCount = AdminNotification::count();

        $typeCounts = [
            'all' => $totalCount,
            'orders' => AdminNotification::whereIn('type', ['new_order', 'online_order'])->count(),
            'pos' => AdminNotification::whereIn('type', ['pos_order', 'pos_quotation'])->count(),
            'customers' => AdminNotification::whereIn('type', ['customer_registered', 'new_user_registration'])->count(),
            'dispatched' => AdminNotification::where('type', 'order_dispatched')->count(),
            'delivered' => AdminNotification::where('type', 'order_delivered')->count(),
            'cancelled' => AdminNotification::where('type', 'order_cancelled')->count(),
            'payments' => AdminNotification::whereIn('type', ['payment_received', 'payment_confirmed'])->count(),
        ];

        return view('admin.notifications.index', compact(
            'notifications',
            'unreadCount',
            'readCount',
            'totalCount',
            'status',
            'type',
            'search',
            'typeCounts'
        ));
    }

    /**
     * Get latest notifications (for top navbar dropdown)
     */
    public function getLatest(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);
        
        $notifications = AdminNotification::orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'link' => $notification->link,
                    'icon' => $notification->icon_class,
                    'badge_color' => $notification->badge_color,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'created_at_formatted' => $notification->created_at->format('d-m-Y h:i A'),
                ];
            });

        $unreadCount = AdminNotification::unread()->count();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Get unread count
     */
    public function getUnreadCount(): JsonResponse
    {
        $count = AdminNotification::unread()->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id): JsonResponse
    {
        try {
            $notification = AdminNotification::findOrFail($id);
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read',
            ], 500);
        }
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread($id): JsonResponse
    {
        try {
            $notification = AdminNotification::findOrFail($id);
            $notification->markAsUnread();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as unread',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as unread',
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        try {
            AdminNotification::unread()->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read',
            ], 500);
        }
    }

    /**
     * Delete notification
     */
    public function destroy($id): JsonResponse
    {
        try {
            $notification = AdminNotification::findOrFail($id);
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification',
            ], 500);
        }
    }

    /**
     * Clear all read notifications
     */
    public function clearRead(): JsonResponse
    {
        try {
            AdminNotification::read()->delete();

            return response()->json([
                'success' => true,
                'message' => 'All read notifications cleared',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear notifications',
            ], 500);
        }
    }
}
