<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'], // fixed unique validation
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:customer,caterer'],
            'business_name' => ['required_if:role,caterer', 'string', 'max:255'],
            'owner_full_name' => ['required_if:role,caterer', 'string', 'max:255'],
            'business_address' => ['required_if:role,caterer', 'string', 'max:255'],
            'business_permit_number' => ['required_if:role,caterer', 'string', 'max:255'],
            'business_permit_file' => ['required_if:role,caterer', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB max
        ]);

        $businessPermitFilePath = null;
        if ($request->hasFile('business_permit_file')) {
            // Store file and get path
            $businessPermitFilePath = $request->file('business_permit_file')->store('business_permits', 'public');
        }

            $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'business_name' => $request->role === 'caterer' ? $request->business_name : null,
        'owner_full_name' => $request->role === 'caterer' ? $request->owner_full_name : null,
        'business_address' => $request->role === 'caterer' ? $request->business_address : null,
        'business_permit_number' => $request->role === 'caterer' ? $request->business_permit_number : null,
        'business_permit_file_path' => $businessPermitFilePath,
        'status' => $request->role === 'caterer' ? 'pending' : 'approved', // Set caterer status 'pending'
    ]);

    event(new Registered($user));

    if ($user->role === 'caterer') {
        // Don't login caterer yet, redirect to a 'pending approval' info page
        return redirect()->route('register.pending');
    } else {
        // Auto-login customers or others
        Auth::login($user);
        return redirect(route('dashboard'));
    }
}
}
