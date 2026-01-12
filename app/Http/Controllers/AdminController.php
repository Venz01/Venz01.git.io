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
        // You can add dashboard analytics & stats here
        return view('admin.dashboard');
    }

    public function userManagement(Request $request)
    {
        $query = User::query();
        
        // Filter by role if requested
        if ($request->has('role') && in_array($request->role, ['customer', 'caterer', 'admin'])) {
            $query->where('role', $request->role);
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
        $caterer->update(['status' => 'approved']);
        
        // LOG THIS ACTION
        ActivityLogger::logAdmin(
            'caterer_approved',
            "Approved caterer application: {$caterer->business_name}",
            [
                'caterer_id' => $caterer->id,
                'caterer_name' => $caterer->name,
                'business_name' => $caterer->business_name,
            ]
        );
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Caterer approved successfully.');
    }

    public function rejectCaterer($id)
    {
        $caterer = User::where('role', 'caterer')->findOrFail($id);
        $caterer->update(['status' => 'rejected']);
        
        // LOG THIS ACTION
        ActivityLogger::logAdmin(
            'caterer_rejected',
            "Rejected caterer application: {$caterer->business_name}",
            [
                'caterer_id' => $caterer->id,
                'caterer_name' => $caterer->name,
                'business_name' => $caterer->business_name,
            ]
        );
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Caterer rejected.');
    }

    // Activity Logs
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(50);

        // Get filter options
        $users = User::select('id', 'name', 'email')->get();
        $types = ActivityLog::distinct()->pluck('type');

        return view('admin.activity-logs', compact('logs', 'users', 'types'));
    }
}