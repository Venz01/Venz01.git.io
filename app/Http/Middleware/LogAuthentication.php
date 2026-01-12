<?php

namespace App\Http\Middleware;

use App\Helpers\ActivityLogger;
use Closure;
use Illuminate\Http\Request;

class LogAuthentication
{
    /**
     * Handle an incoming request.
     * This logs successful logins automatically.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Check if user just logged in (not already logged in this session)
        if (auth()->check() && !session('auth_login_logged')) {
            $user = auth()->user();
            
            ActivityLogger::logAuth(
                'login',
                "{$user->name} ({$user->role}) logged in successfully",
                auth()->id()
            );
            
            // Mark as logged for this session
            session(['auth_login_logged' => true]);
        }
        
        return $response;
    }
}