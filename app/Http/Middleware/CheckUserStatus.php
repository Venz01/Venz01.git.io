<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check if user is blocked
            if ($user->status === 'blocked') {
                Auth::logout();
                
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Your account has been permanently blocked due to violations of our terms of service. Please contact our support team for more information.',
                ]);
            }
            
            // Check if user is suspended
            if ($user->status === 'suspended') {
                Auth::logout();
                
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $message = $user->role === 'caterer' 
                    ? 'Your account has been temporarily suspended. There may be an issue with your account that requires attention. Please contact the administrator for assistance and further details.'
                    : 'Your account has been temporarily suspended. Please contact our support team to resolve this issue.';

                return redirect()->route('login')->withErrors([
                    'email' => $message,
                ]);
            }
            
            // For caterers, also check if they're rejected
            if ($user->role === 'caterer' && $user->status === 'rejected') {
                Auth::logout();
                
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Your caterer application has been reviewed and unfortunately was not approved at this time. Please contact the administrator if you have questions or would like to reapply.',
                ]);
            }
        }

        return $next($request);
    }
}