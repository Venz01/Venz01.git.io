<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
    
    // Optional: Log the status change
    \Log::info("User status changed", [
        'admin_id' => auth()->id(),
        'admin_name' => auth()->user()->name,
        'user_id' => $user->id,
        'user_name' => $user->name,
        'user_role' => $user->role,
        'old_status' => $oldStatus,
        'new_status' => $newStatus,
    ]);
    
    $message = match($newStatus) {
        'approved' => 'User has been activated successfully.',
        'suspended' => 'User has been suspended successfully.',
        'blocked' => 'User has been blocked successfully.',
        'rejected' => 'Caterer has been rejected.',
        default => 'User status updated successfully.'
    };
    
    return redirect()->back()->with('success', $message);
}

    public function showCaterer($id)
{
    $caterer = \App\Models\User::where('role', 'caterer')->findOrFail($id);
    return view('admin.caterers.show', compact('caterer'));
}

public function approveCaterer($id)
{
    $caterer = \App\Models\User::where('role', 'caterer')->findOrFail($id);
    $caterer->update(['status' => 'approved']);
    
    // Optional: Send notification to caterer
    
    return redirect()->route('admin.dashboard')
        ->with('success', 'Caterer approved successfully.');
}

public function rejectCaterer($id)
{
    $caterer = \App\Models\User::where('role', 'caterer')->findOrFail($id);
    $caterer->update(['status' => 'rejected']);
    
    // Optional: Send notification to caterer
    
    return redirect()->route('admin.dashboard')
        ->with('success', 'Caterer rejected.');
}

}
