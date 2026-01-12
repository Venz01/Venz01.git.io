<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspendedCaterer
{
    /**
     * Handle an incoming request.
     * This middleware specifically handles suspended caterers after login
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Only check for caterers
            if ($user->role === 'caterer') {
                // If caterer is suspended - show specific message
                if ($user->status === 'suspended') {
                    Auth::logout();
                    
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->withErrors([
                        'email' => 'Your account has been temporarily suspended. There may be an issue with your account that requires attention. Please contact the administrator for assistance and further details.',
                    ]);
                }
                
                // If caterer is blocked
                if ($user->status === 'blocked') {
                    Auth::logout();
                    
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->withErrors([
                        'email' => 'Your account has been permanently blocked due to violations of our terms of service. Please contact our support team for more information.',
                    ]);
                }
                
                // If caterer is still pending
                if ($user->status === 'pending') {
                    Auth::logout();
                    
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('register.pending');
                }
                
                // If caterer was rejected
                if ($user->status === 'rejected') {
                    Auth::logout();
                    
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->withErrors([
                        'email' => 'Your caterer application has been reviewed and unfortunately was not approved at this time. Please contact the administrator if you have questions or would like to reapply.',
                    ]);
                if ($user->status === 'suspended') {
                    ActivityLogger::logAuth('login_blocked', "Suspended user attempted to login: {$user->email}", $user->id);
                    
                    Auth::logout();
                    // ... rest of code
}
                }
            }
        }

        return $next($request);
    }
}