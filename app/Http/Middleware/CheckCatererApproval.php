<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCatererApproval
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // If user is caterer but status is not approved
        if ($user && $user->role === 'caterer' && $user->status !== 'approved') {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'approval' => 'Your account is pending approval by the administrator. Please wait.',
            ]);
        }

        return $next($request);
    }
}
