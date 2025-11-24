<?php

namespace App\Http\Controllers;

use App\Models\PortfolioImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Load portfolio images for caterers
        if ($user->isCaterer()) {
            $user->load('portfolioImages');
        }
        
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
        
        // Caterer-specific validation
        if ($user->isCaterer()) {
            $rules = array_merge($rules, [
                'business_name' => 'required|string|max:255',
                'owner_full_name' => 'required|string|max:255',
                'business_address' => 'required|string|max:500',
                'contact_number' => 'required|string|max:20',
                'services_offered' => 'nullable|string|max:1000',
                'cuisine_types' => 'nullable|array',
                'cuisine_types.*' => 'string|max:100',
                'years_of_experience' => 'nullable|integer|min:0|max:100',
                'team_size' => 'nullable|integer|min:1|max:1000',
                'service_areas' => 'nullable|array',
                'service_areas.*' => 'string|max:100',
                'facebook_link' => 'nullable|url|max:255',
                'instagram_link' => 'nullable|url|max:255',
                'website_link' => 'nullable|url|max:255',
                'other_contact' => 'nullable|string|max:255',
                'business_hours_start' => 'nullable|date_format:H:i',
                'business_hours_end' => 'nullable|date_format:H:i|after:business_hours_start',
                'business_days' => 'nullable|array',
                'business_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
                'minimum_order' => 'nullable|numeric|min:0',
                'maximum_capacity' => 'nullable|numeric|min:0',
                'offers_delivery' => 'boolean',
                'offers_setup' => 'boolean',
                'special_features' => 'nullable|string|max:1000',
            ]);
        }
        
        // Customer-specific validation
        if ($user->isCustomer()) {
            $rules = array_merge($rules, [
                'preferred_cuisine' => 'nullable|string|max:255',
                'default_address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
            ]);
        }
        
        $validated = $request->validate($rules);
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }
        
        // Handle checkboxes for caterers
        if ($user->isCaterer()) {
            $validated['offers_delivery'] = $request->has('offers_delivery');
            $validated['offers_setup'] = $request->has('offers_setup');
        }
        
        $user->fill($validated);
        
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        
        $user->save();
        
        return Redirect::route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Upload portfolio image (Caterers only)
     */
    public function uploadPortfolio(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
        ]);
        
        $imagePath = $request->file('image')->store('portfolio', 'public');
        
        // Get the next order number
        $maxOrder = PortfolioImage::where('user_id', auth()->id())->max('order') ?? 0;
        
        PortfolioImage::create([
            'user_id' => auth()->id(),
            'image_path' => $imagePath,
            'title' => $request->title,
            'description' => $request->description,
            'is_featured' => $request->has('is_featured'),
            'order' => $maxOrder + 1,
        ]);
        
        return Redirect::route('profile.edit')->with('success', 'Portfolio image uploaded successfully!');
    }

    /**
     * Delete portfolio image (Caterers only)
     */
    public function deletePortfolio($id): RedirectResponse
    {
        $image = PortfolioImage::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();
        
        // Delete the file
        Storage::disk('public')->delete($image->image_path);
        
        // Delete the record
        $image->delete();
        
        return Redirect::route('profile.edit')->with('success', 'Portfolio image deleted successfully!');
    }

    /**
     * Update portfolio image order
     */
    public function updatePortfolioOrder(Request $request): RedirectResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:portfolio_images,id',
        ]);
        
        foreach ($request->order as $index => $imageId) {
            PortfolioImage::where('id', $imageId)
                ->where('user_id', auth()->id())
                ->update(['order' => $index + 1]);
        }
        
        return Redirect::route('profile.edit')->with('success', 'Portfolio order updated successfully!');
    }

    /**
     * Toggle portfolio image as featured
     */
    public function toggleFeatured($id): RedirectResponse
    {
        $image = PortfolioImage::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();
        
        $image->is_featured = !$image->is_featured;
        $image->save();
        
        return Redirect::route('profile.edit')->with('success', 'Portfolio image updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}