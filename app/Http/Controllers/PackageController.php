<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\MenuItem;

class PackageController extends Controller
{
    /**
     * Calculate package price based on menu items
     */
    private function calculatePackagePrice(array $menuItemIds)
    {
        $foodCost = MenuItem::whereIn('id', $menuItemIds)->sum('price');
        $laborAndUtilities = $foodCost * 0.20;
        $equipmentTransport = $foodCost * 0.10;
        $profitMargin = $foodCost * 0.25;
        $totalPrice = $foodCost + $laborAndUtilities + $equipmentTransport + $profitMargin;
        $roundedPrice = round($totalPrice / 5) * 5;
        
        return $roundedPrice;
    }

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
                    $menuItem = MenuItem::find($value);
                    if (!$menuItem || $menuItem->user_id !== auth()->id()) {
                        $fail('One or more selected menu items are invalid.');
                    }
                }
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'dietary_tags' => 'nullable|array',
            'dietary_tags.*' => 'string|in:no_pork,vegetarian,vegan,halal,gluten_free,dairy_free,seafood_free',
        ]);

        try {
            $calculatedPrice = $this->calculatePackagePrice($request->menu_items);

            // Handle image upload to Cloudinary
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadToCloudinary($request->file('image'), 'packages');
            }

            $package = Package::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description ?? '',
                'price' => $calculatedPrice,
                'pax' => $request->pax,
                'status' => 'active',
                'image_path' => $imagePath,
                'dietary_tags' => $request->input('dietary_tags', []),
            ]);

            $package->items()->attach($request->menu_items);

            return back()->with('success', 'Package created successfully! Price per head: ₱' . number_format($calculatedPrice, 2));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create package: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Package $package)
    {
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
                    $menuItem = MenuItem::find($value);
                    if (!$menuItem || $menuItem->user_id !== auth()->id()) {
                        $fail('One or more selected menu items are invalid.');
                    }
                }
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'dietary_tags' => 'nullable|array',
            'dietary_tags.*' => 'string|in:no_pork,vegetarian,vegan,halal,gluten_free,dairy_free,seafood_free',
        ]);

        try {
            $menuItems = $request->input('menu_items', []);
            $calculatedPrice = count($menuItems) > 0 ? $this->calculatePackagePrice($menuItems) : 0;

            // Handle image replacement
            $imagePath = $package->image_path;
            if ($request->hasFile('image')) {
                // Delete old image from Cloudinary
                if ($package->image_path) {
                    try {
                        $this->deleteFromCloudinary($package->image_path);
                    } catch (\Exception $e) {
                        // Continue
                    }
                }
                
                // Upload new image
                $imagePath = $this->uploadToCloudinary($request->file('image'), 'packages');
            }

            $package->update([
                'name' => $request->name,
                'description' => $request->description ?? '',
                'price' => $calculatedPrice,
                'pax' => $request->pax,
                'image_path' => $imagePath,
                'dietary_tags' => $request->input('dietary_tags', []),
            ]);

            $package->items()->sync($menuItems);

            return back()->with('success', 'Package updated successfully! New price per head: ₱' . number_format($calculatedPrice, 2));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update package: ' . $e->getMessage());
        }
    }

    public function destroy(Package $package)
    {
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Delete image from Cloudinary
            if ($package->image_path) {
                try {
                    $this->deleteFromCloudinary($package->image_path);
                } catch (\Exception $e) {
                    // Continue
                }
            }

            $package->items()->detach();
            $package->delete();

            return back()->with('success', 'Package deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete package: ' . $e->getMessage());
        }
    }

    public function toggle(Package $package)
    {
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $package->status = $package->status === 'active' ? 'inactive' : 'active';
            $package->save();

            $statusText = $package->status === 'active' ? 'activated' : 'deactivated';
            return back()->with('success', "Package {$statusText} successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update package status.');
        }
    }

    public function getItems(Package $package)
    {
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json([
            'items' => $package->items()->pluck('menu_items.id')->toArray()
        ]);
    }

    public function getPriceBreakdown(Package $package)
    {
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
     * Upload image to Cloudinary using direct API call
     */
    private function uploadToCloudinary($file, $folder)
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');
        
        if (!$cloudName || !$apiKey || !$apiSecret) {
            throw new \Exception('Cloudinary credentials not configured.');
        }
        
        $timestamp = time();
        $publicId = $folder . '/' . uniqid();
        
        $signatureString = "folder={$folder}&public_id={$publicId}&timestamp={$timestamp}{$apiSecret}";
        $signature = sha1($signatureString);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'file' => new \CURLFile($file->getRealPath()),
            'api_key' => $apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature,
            'folder' => $folder,
            'public_id' => $publicId,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            throw new \Exception('Cloudinary upload failed: ' . $response);
        }
        
        $result = json_decode($response, true);
        return $result['secure_url'];
    }
    
    /**
     * Delete image from Cloudinary
     */
    private function deleteFromCloudinary($url)
    {
        if (strpos($url, 'cloudinary.com') === false) {
            return;
        }
        
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');
        
        if (!$cloudName || !$apiKey || !$apiSecret) {
            return;
        }
        
        $publicId = $this->getPublicIdFromUrl($url);
        if (!$publicId) {
            return;
        }
        
        $timestamp = time();
        $signatureString = "public_id={$publicId}&timestamp={$timestamp}{$apiSecret}";
        $signature = sha1($signatureString);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'public_id' => $publicId,
            'api_key' => $apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_exec($ch);
        curl_close($ch);
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
        array_shift($pathParts);
        $publicId = implode('/', $pathParts);
        $publicId = preg_replace('/\.[^.]+$/', '', $publicId);
        
        return $publicId;
    }
}