<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Helpers\ActivityLogger;

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
            'recent_activity' => ActivityLog::with('user')->latest()->take(10)->get(),
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
        
        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('business_name', 'like', '%' . $request->search . '%');
            });
        }
        
        // Order by created date (newest first)
        $query->orderBy('created_at', 'desc');
        
        $users = $query->paginate(15);
        
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
}