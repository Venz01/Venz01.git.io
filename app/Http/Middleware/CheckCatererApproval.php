<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCatererApproval
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Only apply to caterers
        if ($user->role !== 'caterer') {
            return $next($request);
        }

        // Check if caterer is approved
        if ($user->status !== 'approved') {
            // Redirect to pending page with appropriate message
            $message = match($user->status) {
                'pending' => 'Your caterer application is still under review. Please wait for admin approval.',
                'rejected' => 'Your caterer application was rejected. Please contact support for more information.',
                'suspended' => 'Your caterer account has been suspended. Please contact support.',
                default => 'Your account is not active. Please contact support.'
            };

            return redirect()->route('register.pending')
                ->with('status_message', $message);
        }

        return $next($request);
    }
}
