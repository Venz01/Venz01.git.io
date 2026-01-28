<?php

namespace App\Http\Controllers;

use App\Models\PortfolioImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
     * Update the user's profile information
     * ALL FIELDS ARE NULLABLE (safe partial updates)
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Base validation
        $rules = [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'contact_number' => 'nullable|string|max:20',
            'other_contact' => 'nullable|string|max:255',
            'facebook_link' => 'nullable|url|max:255',
        ];

        // Caterer fields
        if ($user->isCaterer()) {
            $rules = array_merge($rules, [
                'business_name' => 'nullable|string|max:255',
                'owner_full_name' => 'nullable|string|max:255',
                'business_address' => 'nullable|string|max:500',
                'services_offered' => 'nullable|string|max:1000',
                'cuisine_types' => 'nullable|array',
                'service_areas' => 'nullable|array',
                'years_of_experience' => 'nullable|integer|min:0|max:100',
                'team_size' => 'nullable|integer|min:1',
                'minimum_order' => 'nullable|numeric|min:0',
                'maximum_capacity' => 'nullable|numeric|min:0',
                'instagram_link' => 'nullable|url|max:255',
                'website_link' => 'nullable|url|max:255',
                'special_features' => 'nullable|string|max:1000',
                'business_hours_start' => 'nullable|string',
                'business_hours_end' => 'nullable|string',
                'business_days' => 'nullable|array',
            ]);
        }

        // Customer fields
        if ($user->isCustomer()) {
            $rules = array_merge($rules, [
                'preferred_cuisine' => 'nullable|string|max:255',
                'default_address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
            ]);
        }

        // Validate request
        $validated = $request->validate($rules);

        // Caterer-specific logic
        if ($user->isCaterer()) {
            $isBusinessUpdate = $request->has('business_name') || $request->has('services_offered');
            $isHoursUpdate = $request->has('business_hours_start')
                || $request->has('business_hours_end')
                || $request->has('business_days');

            if ($isBusinessUpdate) {
                $validated['offers_delivery'] = $request->has('offers_delivery') ? 1 : 0;
                $validated['offers_setup'] = $request->has('offers_setup') ? 1 : 0;

                $validated['cuisine_types'] = $request->has('cuisine_types')
                    ? $request->cuisine_types
                    : [];

                $validated['service_areas'] = $request->has('service_areas')
                    ? $request->service_areas
                    : [];
            }

            if ($isHoursUpdate) {
                $validated['business_days'] = $request->input('business_days', []);

                if ($request->filled('business_hours_start')) {
                    $validated['business_hours_start'] = $request->business_hours_start;
                }

                if ($request->filled('business_hours_end')) {
                    $validated['business_hours_end'] = $request->business_hours_end;
                }
            }
        }

        // Update only validated fields
        foreach ($validated as $key => $value) {
            $user->$key = $value;
        }

        // Reset email verification if email changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update or remove profile photo
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Remove photo
        if ($request->has('remove_photo') && $request->remove_photo) {
            if ($user->profile_photo) {
                // Delete from Cloudinary
                try {
                    $publicId = $this->getPublicIdFromUrl($user->profile_photo);
                    if ($publicId) {
                        Cloudinary::destroy($publicId);
                    }
                } catch (\Exception $e) {
                    // Continue even if deletion fails
                }
                
                $user->profile_photo = null;
                $user->save();
            }

            return Redirect::route('profile.edit')
                ->with('photo_success', 'Profile photo removed successfully.');
        }

        // Validate upload
        $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        // Upload new photo
        if ($request->hasFile('profile_photo')) {
            // Delete old photo from Cloudinary
            if ($user->profile_photo) {
                try {
                    $publicId = $this->getPublicIdFromUrl($user->profile_photo);
                    if ($publicId) {
                        Cloudinary::destroy($publicId);
                    }
                } catch (\Exception $e) {
                    // Continue even if deletion fails
                }
            }

            // Upload to Cloudinary
            $uploadedFile = Cloudinary::upload($request->file('profile_photo')->getRealPath(), [
                'folder' => 'profile-photos'
            ]);
            $user->profile_photo = $uploadedFile->getSecurePath();
            $user->save();

            return Redirect::route('profile.edit')
                ->with('photo_success', 'Profile photo updated successfully!');
        }

        return Redirect::route('profile.edit');
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
            'is_featured' => 'nullable|boolean',
        ]);

        // Upload to Cloudinary
        $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
            'folder' => 'portfolio'
        ]);
        $imagePath = $uploadedFile->getSecurePath();

        $maxOrder = PortfolioImage::where('user_id', auth()->id())->max('order') ?? 0;

        PortfolioImage::create([
            'user_id' => auth()->id(),
            'image_path' => $imagePath,
            'title' => $request->title,
            'description' => $request->description,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'order' => $maxOrder + 1,
        ]);

        return Redirect::route('profile.edit')
            ->with('success', 'Portfolio image uploaded successfully!');
    }

    /**
     * Toggle featured image
     */
    public function toggleFeatured($id): RedirectResponse
    {
        $image = PortfolioImage::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $image->is_featured = !$image->is_featured;
        $image->save();

        return Redirect::route('profile.edit')
            ->with('success', 'Featured status updated!');
    }

    /**
     * Delete portfolio image
     */
    public function deletePortfolio($id): RedirectResponse
    {
        $image = PortfolioImage::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        // Delete from Cloudinary
        try {
            $publicId = $this->getPublicIdFromUrl($image->image_path);
            if ($publicId) {
                Cloudinary::destroy($publicId);
            }
        } catch (\Exception $e) {
            // Continue even if deletion fails
        }

        $image->delete();

        return Redirect::route('profile.edit')
            ->with('success', 'Portfolio image deleted successfully!');
    }

    /**
     * Update portfolio order
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

        return Redirect::route('profile.edit')
            ->with('success', 'Portfolio order updated successfully!');
    }

    /**
     * Delete user account
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
    
    /**
     * Extract Cloudinary public_id from URL
     */
    private function getPublicIdFromUrl($url)
    {
        if (strpos($url, 'cloudinary.com') === false) {
            return null;
        }
        
        $parts = explode('/upload/', $url);
        if (count($parts) < 2) {
            return null;
        }
        
        $pathParts = explode('/', $parts[1]);
        array_shift($pathParts); // Remove version
        $publicId = implode('/', $pathParts);
        $publicId = preg_replace('/\.[^.]+$/', '', $publicId);
        
        return $publicId;
    }
}