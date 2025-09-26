<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check if user has the required role
        if ($user->role !== $role) {
            // Log the unauthorized attempt for security monitoring
            logger()->warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'required_role' => $role,
                'url' => $request->url(),
                'ip' => $request->ip(),
            ]);

            // Return appropriate response based on request type
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Access denied. Insufficient permissions.'
                ], 403);
            }

            // Redirect to appropriate dashboard instead of showing 403
            return match($user->role) {
                'admin' => redirect()->route('admin.dashboard')
                    ->with('error', 'Access denied. You do not have permission to access that page.'),
                'caterer' => redirect()->route('caterer.dashboard')
                    ->with('error', 'Access denied. You do not have permission to access that page.'),
                'customer' => redirect()->route('customer.dashboard')
                    ->with('error', 'Access denied. You do not have permission to access that page.'),
                default => redirect()->route('dashboard')
                    ->with('error', 'Access denied. You do not have permission to access that page.'),
            };
        }

        return $next($request);
    }
}
