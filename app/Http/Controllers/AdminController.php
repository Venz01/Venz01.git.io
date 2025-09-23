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

    public function userManagement()
    {
        // Fetch all users, optionally with pagination for efficiency
        $users = User::orderBy('created_at', 'desc')->paginate(20);
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

}
