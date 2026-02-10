<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Helpers\ActivityLogger;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Dashboard analytics & stats
        $stats = [
            'total_users' => User::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_caterers' => User::where('role', 'caterer')->count(),
            'pending_caterers' => User::where('role', 'caterer')->where('status', 'pending')->count(),
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'week_logs' => ActivityLog::where('created_at', '>=', now()->subWeek())->count(),
            'recent_activity' => ActivityLog::with('user')->latest()->take(5)->get(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }

    public function userManagement(Request $request)
    {
        $query = User::query();
        
        // Filter by role if requested
        if ($request->has('role') && in_array($request->role, ['customer', 'caterer', 'admin'])) {
            $query->where('role', $request->role);
        }
        
        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhere('business_name', 'like', $searchTerm);
            });
        }
        
        // Order by created date (newest first)
        $query->orderBy('created_at', 'desc');
        
        $users = $query->paginate(15);
        
        // Append query parameters to pagination links
        $users->appends($request->only(['role', 'search']));
        
        return view('admin.users', compact('users'));
    }

    public function updateUserStatus(Request $request, $userId)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,blocked,approved,rejected,pending'
        ]);
        
        $user = User::findOrFail($userId);
        
        // Prevent admin from suspending themselves
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot change your own status.');
        }
        
        // Prevent changing other admin's status unless you're a super admin
        if ($user->role === 'admin' && $user->id !== auth()->id()) {
            return redirect()->back()->with('error', 'You cannot change another admin\'s status.');
        }
        
        $oldStatus = $user->status;
        $newStatus = $request->status;
        
        // Map status based on user role
        if ($user->role === 'caterer') {
            // For caterers, use approved/pending/rejected/suspended/blocked
            if ($newStatus === 'active') {
                $newStatus = 'approved';
            }
        } else {
            // For customers and admins, use approved/suspended/blocked
            if ($newStatus === 'active') {
                $newStatus = 'approved';
            }
        }
        
        $user->status = $newStatus;
        $user->save();
        
        // LOG THIS ACTION
        ActivityLogger::logAdmin(
            'user_status_changed',
            "Changed {$user->name}'s status from {$oldStatus} to {$newStatus}",
            [
                'target_user_id' => $user->id,
                'target_user_name' => $user->name,
                'target_user_email' => $user->email,
                'target_user_role' => $user->role,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]
        );
        
        $message = match($newStatus) {
            'approved' => 'User has been activated successfully and can now log in.',
            'suspended' => 'User has been suspended and cannot log in.',
            'blocked' => 'User has been blocked and cannot log in.',
            'rejected' => 'Caterer application has been rejected.',
            default => 'User status updated successfully.'
        };
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Delete a single user
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        
        // Delete user and all related data
        $user->delete();

        return back()->with('success', "User {$userName} has been deleted successfully.");
    }

    /**
     * Handle bulk actions (activate, suspend, delete)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|json',
            'action' => 'required|in:activate,suspend,delete'
        ]);

        $userIds = json_decode($request->user_ids, true);
        
        if (empty($userIds) || !is_array($userIds)) {
            return back()->with('error', 'No users selected.');
        }

        // Remove current admin from the list to prevent self-action
        $userIds = array_filter($userIds, function($id) {
            return $id != auth()->id();
        });

        if (empty($userIds)) {
            return back()->with('error', 'Cannot perform bulk actions on your own account.');
        }

        $count = 0;
        $action = $request->action;

        DB::beginTransaction();
        try {
            switch ($action) {
                case 'activate':
                    $count = User::whereIn('id', $userIds)->update(['status' => 'active']);
                    $message = "{$count} user(s) have been activated successfully.";
                    break;

                case 'suspend':
                    $count = User::whereIn('id', $userIds)->update(['status' => 'suspended']);
                    $message = "{$count} user(s) have been suspended successfully.";
                    break;

                case 'delete':
                    $count = User::whereIn('id', $userIds)->count();
                    User::whereIn('id', $userIds)->delete();
                    $message = "{$count} user(s) have been deleted successfully.";
                    break;

                default:
                    throw new \Exception('Invalid action');
            }

            DB::commit();
            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while performing the bulk action: ' . $e->getMessage());
        }
    }

    public function showCaterer($id)
    {
        $caterer = User::where('role', 'caterer')->findOrFail($id);
        return view('admin.caterers.show', compact('caterer'));
    }

    public function approveCaterer($id)
    {
        $caterer = User::where('role', 'caterer')->findOrFail($id);
        $oldStatus = $caterer->status;
        $caterer->update(['status' => 'approved']);
        
        // LOG THIS ACTION
        ActivityLogger::logAdmin(
            'caterer_approved',
            "Approved caterer application: {$caterer->business_name}",
            [
                'caterer_id' => $caterer->id,
                'caterer_name' => $caterer->name,
                'caterer_email' => $caterer->email,
                'business_name' => $caterer->business_name,
                'old_status' => $oldStatus,
                'new_status' => 'approved',
            ]
        );
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Caterer approved successfully.');
    }

    public function rejectCaterer($id)
    {
        $caterer = User::where('role', 'caterer')->findOrFail($id);
        $oldStatus = $caterer->status;
        $caterer->update(['status' => 'rejected']);
        
        // LOG THIS ACTION
        ActivityLogger::logAdmin(
            'caterer_rejected',
            "Rejected caterer application: {$caterer->business_name}",
            [
                'caterer_id' => $caterer->id,
                'caterer_name' => $caterer->name,
                'caterer_email' => $caterer->email,
                'business_name' => $caterer->business_name,
                'old_status' => $oldStatus,
                'new_status' => 'rejected',
            ]
        );
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Caterer rejected.');
    }

    /**
     * Activity Logs - Enhanced with filters
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by type (authentication, admin, booking, payment, etc.)
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by action (login, logout, created, updated, etc.)
        if ($request->has('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }

        // Filter by specific user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by role (customer, caterer, admin)
        if ($request->has('role') && $request->role !== 'all') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description and IP address
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->paginate(50);

        // Get filter options
        $users = User::select('id', 'name', 'email', 'role')
                     ->orderBy('name')
                     ->get();
        $types = ActivityLog::distinct()->pluck('type')->sort();
        $actions = ActivityLog::distinct()->pluck('action')->sort();
        $roles = ['customer', 'caterer', 'admin'];

        return view('admin.activity-logs', compact('logs', 'users', 'types', 'actions', 'roles'));
    }

    /**
     * Export activity logs to CSV
     */
    public function exportActivityLogs(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Apply same filters as activityLogs() method
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        if ($request->has('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('role') && $request->role !== 'all') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->get();

        // LOG THIS EXPORT ACTION
        ActivityLogger::logAdmin(
            'logs_exported',
            "Exported " . $logs->count() . " activity logs to CSV",
            [
                'total_logs' => $logs->count(),
                'filters_applied' => $request->all(),
            ]
        );

        $filename = 'activity_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Date/Time', 
                'User', 
                'Role', 
                'Type', 
                'Action', 
                'Description', 
                'IP Address',
                'User Agent'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->name : 'System',
                    $log->user ? $log->user->role : 'N/A',
                    $log->type,
                    $log->action,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete old activity logs (admin cleanup tool)
     */
    public function cleanupLogs(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30|max:365'
        ]);

        $days = $request->days;
        $date = now()->subDays($days);
        
        $count = ActivityLog::where('created_at', '<', $date)->count();
        ActivityLog::where('created_at', '<', $date)->delete();

        // LOG THIS CLEANUP ACTION
        ActivityLogger::logAdmin(
            'logs_cleaned',
            "Deleted {$count} activity logs older than {$days} days",
            [
                'logs_deleted' => $count,
                'days_threshold' => $days,
                'cutoff_date' => $date->format('Y-m-d H:i:s'),
            ]
        );

        return redirect()->back()->with('success', "Successfully deleted {$count} old activity logs.");
    }

    /**
     * View detailed log entry
     */
    public function showLog($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        return view('admin.activity-log-detail', compact('log'));
    }

    /**
     * Get activity log details (AJAX endpoint)
     */
    public function getActivityLogDetails($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        
        return response()->json([
            'id' => $log->id,
            'user' => $log->user ? [
                'name' => $log->user->name,
                'email' => $log->user->email,
                'role' => $log->user->role,
            ] : null,
            'type' => $log->type,
            'action' => $log->action,
            'description' => $log->description,
            'properties' => $log->properties,
            'ip_address' => $log->ip_address,
            'user_agent' => $log->user_agent,
            'created_at' => $log->created_at->toISOString(),
        ]);
    }

    /**
     * Display feedback and ratings management page
     */
    public function feedbackRatings(Request $request)
    {
        $query = Review::with(['customer', 'caterer', 'booking', 'reviewer'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            switch ($request->status) {
                case 'approved':
                    $query->where('admin_status', 'approved');
                    break;
                case 'flagged':
                    $query->where('admin_status', 'flagged');
                    break;
                case 'under_review':
                    $query->where('admin_status', 'under_review');
                    break;
                case 'removed':
                    $query->where('admin_status', 'removed');
                    break;
                case 'needs_attention':
                    $query->whereIn('admin_status', ['flagged', 'under_review']);
                    break;
            }
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating !== 'all') {
            $query->where('rating', $request->rating);
        }

        // Filter by caterer
        if ($request->has('caterer') && $request->caterer) {
            $query->where('caterer_id', $request->caterer);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhere('caterer_response', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('caterer', function($q) use ($search) {
                      $q->where('business_name', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $reviews = $query->paginate(15)->withQueryString();

        // Calculate statistics
        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('admin_status', 'approved')->count(),
            'flagged' => Review::where('admin_status', 'flagged')->count(),
            'under_review' => Review::where('admin_status', 'under_review')->count(),
            'removed' => Review::where('admin_status', 'removed')->count(),
            'needs_attention' => Review::whereIn('admin_status', ['flagged', 'under_review'])->count(),
            'average_rating' => round(Review::where('admin_status', 'approved')->avg('rating') ?? 0, 1),
            'low_rated' => Review::whereIn('rating', [1, 2])->where('admin_status', 'approved')->count(),
            'caterers_warned' => Review::where('caterer_warned', true)->distinct('caterer_id')->count('caterer_id'),
        ];

        // Get all caterers for filter dropdown
        $caterers = User::where('role', 'caterer')
            ->where('status', 'approved')
            ->orderBy('business_name')
            ->get();

        return view('admin.feedback-ratings', compact('reviews', 'stats', 'caterers'));
    }

    /**
     * Show detailed review information
     */
    public function showReview(Review $review)
    {
        $review->load(['customer', 'caterer', 'booking', 'reviewer']);
        
        // Get caterer's review history
        $catererReviews = Review::where('caterer_id', $review->caterer_id)
            ->where('id', '!=', $review->id)
            ->with(['customer', 'booking'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $catererStats = [
            'total_reviews' => Review::where('caterer_id', $review->caterer_id)->count(),
            'average_rating' => round(Review::where('caterer_id', $review->caterer_id)
                ->where('admin_status', 'approved')->avg('rating') ?? 0, 1),
            'flagged_count' => Review::where('caterer_id', $review->caterer_id)
                ->where('admin_status', 'flagged')->count(),
            'warnings_count' => Review::where('caterer_id', $review->caterer_id)
                ->where('caterer_warned', true)->count(),
        ];

        return view('admin.review-details', compact('review', 'catererReviews', 'catererStats'));
    }

    /**
     * Approve a review
     */
    public function approveReview(Request $request, Review $review)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'admin_status' => 'approved',
            'is_approved' => true,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'admin_reviewed_at' => now(),
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'admin',
            'action' => 'review_approved',
            'description' => "Approved review #{$review->id} from {$review->customer->name} for {$review->caterer->business_name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Review has been approved successfully.');
    }

    /**
     * Flag a review as inappropriate
     */
    public function flagReview(Request $request, Review $review)
    {
        $request->validate([
            'flagged_reason' => 'required|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
            'warn_caterer' => 'boolean',
        ]);

        $review->update([
            'admin_status' => 'flagged',
            'is_approved' => false,
            'flagged_reason' => $request->flagged_reason,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'admin_reviewed_at' => now(),
        ]);

        // Warn caterer if requested
        if ($request->warn_caterer) {
            $review->update([
                'caterer_warned' => true,
                'caterer_warned_at' => now(),
            ]);

            // Send warning notification to caterer
            try {
                $notificationService = app(NotificationService::class);
                $notificationService->notifyCatererWarning($review, $request->flagged_reason);
            } catch (\Exception $e) {
                \Log::error('Failed to send caterer warning notification', [
                    'review_id' => $review->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'admin',
            'action' => 'review_flagged',
            'description' => "Flagged review #{$review->id} - Reason: {$request->flagged_reason}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Review has been flagged successfully.' . ($request->warn_caterer ? ' Caterer has been warned.' : ''));
    }

    /**
     * Remove a review
     */
    public function removeReview(Request $request, Review $review)
    {
        $request->validate([
            'removal_reason' => 'required|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
            'warn_caterer' => 'boolean',
        ]);

        $review->update([
            'admin_status' => 'removed',
            'is_approved' => false,
            'flagged_reason' => $request->removal_reason,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'admin_reviewed_at' => now(),
        ]);

        // Warn caterer if requested
        if ($request->warn_caterer) {
            $review->update([
                'caterer_warned' => true,
                'caterer_warned_at' => now(),
            ]);

            // Send warning notification to caterer
            try {
                $notificationService = app(NotificationService::class);
                $notificationService->notifyCatererWarning($review, $request->removal_reason);
            } catch (\Exception $e) {
                \Log::error('Failed to send caterer warning notification', [
                    'review_id' => $review->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'admin',
            'action' => 'review_removed',
            'description' => "Removed review #{$review->id} - Reason: {$request->removal_reason}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Review has been removed successfully.' . ($request->warn_caterer ? ' Caterer has been warned.' : ''));
    }

    /**
     * Restore a removed review
     */
    public function restoreReview(Request $request, Review $review)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'admin_status' => 'approved',
            'is_approved' => true,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'admin_reviewed_at' => now(),
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'admin',
            'action' => 'review_restored',
            'description' => "Restored review #{$review->id}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Review has been restored successfully.');
    }

    /**
     * Bulk action on multiple reviews
     */
    public function bulkReviewAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,flag,remove,delete',
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id',
            'reason' => 'required_if:action,flag,remove|string|max:1000',
        ]);

        $reviewIds = $request->review_ids;
        $action = $request->action;
        $successCount = 0;

        foreach ($reviewIds as $reviewId) {
            $review = Review::find($reviewId);
            if (!$review) continue;

            switch ($action) {
                case 'approve':
                    $review->update([
                        'admin_status' => 'approved',
                        'is_approved' => true,
                        'reviewed_by' => auth()->id(),
                        'admin_reviewed_at' => now(),
                    ]);
                    $successCount++;
                    break;

                case 'flag':
                    $review->update([
                        'admin_status' => 'flagged',
                        'is_approved' => false,
                        'flagged_reason' => $request->reason,
                        'reviewed_by' => auth()->id(),
                        'admin_reviewed_at' => now(),
                    ]);
                    $successCount++;
                    break;

                case 'remove':
                    $review->update([
                        'admin_status' => 'removed',
                        'is_approved' => false,
                        'flagged_reason' => $request->reason,
                        'reviewed_by' => auth()->id(),
                        'admin_reviewed_at' => now(),
                    ]);
                    $successCount++;
                    break;

                case 'delete':
                    $review->delete();
                    $successCount++;
                    break;
            }
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'admin',
            'action' => 'bulk_review_action',
            'description' => "Performed bulk action '{$action}' on {$successCount} reviews",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', "Bulk action completed successfully on {$successCount} reviews.");
    }
}