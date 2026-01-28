<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\MenuItem;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PackageController extends Controller
{
    /**
     * Calculate package price based on menu items
     * Formula:
     * - Food Cost = Sum of all menu item prices
     * - Labor & Utilities = 20% of Food Cost
     * - Equipment & Transport = 10% of Food Cost
     * - Profit Margin = 25% of Food Cost
     * - Total = Food Cost + All Markups (rounded to nearest 5)
     */
    private function calculatePackagePrice(array $menuItemIds)
    {
        // Get total food cost from selected menu items
        $foodCost = MenuItem::whereIn('id', $menuItemIds)->sum('price');
        
        // Calculate markups
        $laborAndUtilities = $foodCost * 0.20;      // 20%
        $equipmentTransport = $foodCost * 0.10;     // 10%
        $profitMargin = $foodCost * 0.25;           // 25%
        
        // Calculate total price per head
        $totalPrice = $foodCost + $laborAndUtilities + $equipmentTransport + $profitMargin;
        
        // Round to nearest 5 pesos
        $roundedPrice = round($totalPrice / 5) * 5;
        
        return $roundedPrice;
    }

    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'pax' => 'required|integer|min:1|max:1000',
            'menu_items' => 'required|array|min:1',
            'menu_items.*' => [
                'exists:menu_items,id',
                function ($attribute, $value, $fail) {
                    // Ensure menu item belongs to authenticated user
                    $menuItem = MenuItem::find($value);
                    if (!$menuItem || $menuItem->user_id !== auth()->id()) {
                        $fail('One or more selected menu items are invalid.');
                    }
                }
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            // Calculate package price automatically
            $calculatedPrice = $this->calculatePackagePrice($request->menu_items);

            // Handle image upload to Cloudinary
            $imagePath = null;
            if ($request->hasFile('image')) {
                $uploadedFile = $request->file('image')->storeOnCloudinary('packages');
                $imagePath = $uploadedFile->getSecurePath();
            }

            $package = Package::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description ?? '',
                'price' => $calculatedPrice,  // Auto-calculated price
                'pax' => $request->pax,
                'status' => 'active',
                'image_path' => $imagePath,
            ]);

            // Attach menu items to package
            $package->items()->attach($request->menu_items);

            return back()->with('success', 'Package created successfully! Price per head: ₱' . number_format($calculatedPrice, 2));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create package. Please try again.');
        }
    }

    /**
     * Update the specified package
     */
    public function update(Request $request, Package $package)
    {
        // Ensure user owns this package
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'pax' => 'required|integer|min:1|max:1000',
            'menu_items' => 'nullable|array',
            'menu_items.*' => [
                'exists:menu_items,id',
                function ($attribute, $value, $fail) {
                    // Ensure menu item belongs to authenticated user
                    $menuItem = MenuItem::find($value);
                    if (!$menuItem || $menuItem->user_id !== auth()->id()) {
                        $fail('One or more selected menu items are invalid.');
                    }
                }
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            // Recalculate package price based on updated menu items
            $menuItems = $request->input('menu_items', []);
            $calculatedPrice = count($menuItems) > 0 ? $this->calculatePackagePrice($menuItems) : 0;

            // Handle image replacement
            $imagePath = $package->image_path;
            if ($request->hasFile('image')) {
                // Delete old image from Cloudinary if it exists
                if ($package->image_path) {
                    try {
                        $publicId = $this->getPublicIdFromUrl($package->image_path);
                        if ($publicId) {
                            Cloudinary::destroy($publicId);
                        }
                    } catch (\Exception $e) {
                        // Continue even if deletion fails
                    }
                }
                
                // Upload new image
                $uploadedFile = $request->file('image')->storeOnCloudinary('packages');
                $imagePath = $uploadedFile->getSecurePath();
            }

            $package->update([
                'name' => $request->name,
                'description' => $request->description ?? '',
                'price' => $calculatedPrice,  // Auto-recalculated price
                'pax' => $request->pax,
                'image_path' => $imagePath,
            ]);

            // Sync menu items (this will replace existing relationships)
            $package->items()->sync($menuItems);

            return back()->with('success', 'Package updated successfully! New price per head: ₱' . number_format($calculatedPrice, 2));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update package. Please try again.');
        }
    }

    /**
     * Remove the specified package
     */
    public function destroy(Package $package)
    {
        // Ensure user owns this package
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Delete associated image from Cloudinary
            if ($package->image_path) {
                try {
                    $publicId = $this->getPublicIdFromUrl($package->image_path);
                    if ($publicId) {
                        Cloudinary::destroy($publicId);
                    }
                } catch (\Exception $e) {
                    // Continue even if deletion fails
                }
            }

            // Detach all menu items
            $package->items()->detach();

            // Delete the package
            $package->delete();

            return back()->with('success', 'Package deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete package. Please try again.');
        }
    }

    /**
     * Toggle package status between active and inactive
     */
    public function toggle(Package $package)
    {
        // Ensure user owns this package
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $package->status = $package->status === 'active' ? 'inactive' : 'active';
            $package->save();

            $statusText = $package->status === 'active' ? 'activated' : 'deactivated';
            return back()->with('success', "Package {$statusText} successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update package status. Please try again.');
        }
    }

    /**
     * Get items for a package
     */
    public function getItems(Package $package)
    {
        // Ensure user owns this package
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json([
            'items' => $package->items()->pluck('menu_items.id')->toArray()
        ]);
    }

    /**
     * Get package price breakdown (for display purposes)
     */
    public function getPriceBreakdown(Package $package)
    {
        // Ensure user owns this package
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $foodCost = $package->items()->sum('price');
        $laborAndUtilities = $foodCost * 0.20;
        $equipmentTransport = $foodCost * 0.10;
        $profitMargin = $foodCost * 0.25;

        return response()->json([
            'food_cost' => $foodCost,
            'labor_utilities' => $laborAndUtilities,
            'equipment_transport' => $equipmentTransport,
            'profit_margin' => $profitMargin,
            'total_per_head' => $package->price,
            'total_package' => $package->price * $package->pax,
            'items' => $package->items->map(function($item) {
                return [
                    'name' => $item->name,
                    'price' => $item->price
                ];
            })
        ]);
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