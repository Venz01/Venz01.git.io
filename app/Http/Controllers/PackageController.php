<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
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
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('packages', 'public');
            }

            $package = Package::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description ?? '',
                'price' => $request->price,
                'pax' => $request->pax,
                'status' => 'active',
                'image_path' => $imagePath,
            ]);

            // Attach menu items to package
            $package->items()->attach($request->menu_items);

            return back()->with('success', 'Package created successfully!');
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
            'price' => 'required|numeric|min:0|max:999999.99',
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
            // Handle image replacement
            $imagePath = $package->image_path;
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($package->image_path) {
                    Storage::disk('public')->delete($package->image_path);
                }
                $imagePath = $request->file('image')->store('packages', 'public');
            }

            $package->update([
                'name' => $request->name,
                'description' => $request->description ?? '',
                'price' => $request->price,
                'pax' => $request->pax,
                'image_path' => $imagePath,
            ]);

            // Sync menu items (this will replace existing relationships)
            if ($request->has('menu_items')) {
                $package->items()->sync($request->menu_items);
            }

            return back()->with('success', 'Package updated successfully!');
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
            // Delete associated image
            if ($package->image_path) {
                Storage::disk('public')->delete($package->image_path);
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
}