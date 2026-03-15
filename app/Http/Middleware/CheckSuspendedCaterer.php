<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ActivityLogger;

class CheckSuspendedCaterer
{
    /**
     * Handle an incoming request.
     * Intercepts caterers whose account status is not 'approved' and
     * forces a logout with an appropriate message.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Only apply to caterers
        if ($user->role !== 'caterer') {
            return $next($request);
        }

        $status = $user->status;

        if ($status === 'approved') {
            return $next($request);
        }

        // Any non-approved status: log, then logout + redirect
        ActivityLogger::logAuth(
            'login_blocked',
            "Caterer access blocked due to status '{$status}': {$user->email}",
            $user->id
        );

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return match ($status) {
            'suspended' => redirect()->route('login')->withErrors([
                'email' => 'Your account has been temporarily suspended. Please contact the administrator for assistance.',
            ]),

            'blocked' => redirect()->route('login')->withErrors([
                'email' => 'Your account has been permanently blocked due to violations of our terms of service. Please contact our support team.',
            ]),

            'pending' => redirect()->route('register.pending'),

            'rejected' => redirect()->route('login')->withErrors([
                'email' => 'Your caterer application was not approved. Please contact the administrator if you have questions or would like to reapply.',
            ]),

            default => redirect()->route('login')->withErrors([
                'email' => 'Your account is not active. Please contact support.',
            ]),
        };
    }
}