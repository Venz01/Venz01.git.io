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

    

    public function updateUserStatus(User $user, Request $request)
    {
        $status = $request->input('status');
        // Only allow valid statuses
        if (in_array($status, ['approved', 'blocked'])) {
            $user->update(['status' => $status]);
        }
        return back()->with('success', 'User status updated.');
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
