<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use App\Providers\RouteServiceProvider;
use App\Helpers\ActivityLogger;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            // LOG FAILED LOGIN ATTEMPT
            ActivityLogger::log(
                'authentication',
                'login_failed',
                "Failed login attempt for email: {$request->email}",
                [
                    'email' => $request->email,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]
            );

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if ($user->status === 'blocked') {
            // LOG BLOCKED USER ATTEMPT
            ActivityLogger::log(
                'security',
                'blocked_login_attempt',
                "Blocked user attempted to login: {$user->name} ({$user->email})",
                [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                ]
            );

            throw ValidationException::withMessages([
                'email' => __('Your account has been blocked. Please contact support.'),
            ]);
        }

        // Check other statuses that should prevent login
        if (in_array($user->status, ['suspended', 'rejected', 'pending'])) {
            // LOG UNAUTHORIZED STATUS ATTEMPT
            ActivityLogger::log(
                'security',
                'unauthorized_login_attempt',
                "User with status '{$user->status}' attempted to login: {$user->name} ({$user->email})",
                [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                    'user_status' => $user->status,
                ]
            );

            $messages = [
                'suspended' => 'Your account has been suspended. Please contact support.',
                'rejected' => 'Your application has been rejected. Please contact support.',
                'pending' => 'Your account is pending approval. Please wait for admin approval.',
            ];

            throw ValidationException::withMessages([
                'email' => __($messages[$user->status]),
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        // LOG SUCCESSFUL LOGIN
        ActivityLogger::logAuth(
            'login',
            "{$user->name} ({$user->role}) logged in successfully",
            $user->id
        );

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        // LOG LOGOUT BEFORE DESTROYING SESSION
        if (auth()->check()) {
            $user = auth()->user();
            ActivityLogger::logAuth(
                'logout',
                "{$user->name} ({$user->role}) logged out",
                $user->id
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}