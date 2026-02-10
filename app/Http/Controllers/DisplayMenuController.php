<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DisplayMenu;
use Illuminate\Support\Facades\Log;

class DisplayMenuController extends Controller
{
    /**
     * Store a newly created display menu
     */
    public function store(Request $request)
    {
           $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0', // Changed from nullable to required
                'unit_type' => 'nullable|string|max:50',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'status' => 'required|in:active,inactive',
            ]);


        try {
            // Handle image upload to Cloudinary
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadToCloudinary($request->file('image'), 'display_menus');
            }

            DisplayMenu::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description ?? '',
                'price' => $request->price,
                'unit_type' => $request->unit_type ?? 'item',
                'image_path' => $imagePath,
                'status' => $request->status,
            ]);

            return back()->with('success', 'Display menu added successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to create display menu', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to add display menu: ' . $e->getMessage());
        }
    }


    public function index()
{
    $categories = Category::where('user_id', auth()->id())->get();
    
    $displayMenus = DisplayMenu::where('user_id', auth()->id())
        ->orderBy('category')
        ->get()
        ->groupBy('category');
    
    // Get unique display menu categories for the dropdown
    $displayCategories = DisplayMenu::where('user_id', auth()->id())
        ->select('category')
        ->distinct()
        ->orderBy('category')
        ->pluck('category')
        ->toArray();
    
    $packages = Package::where('user_id', auth()->id())->with('items')->get();
    
    return view('caterer.menu-items', compact('categories', 'displayMenus', 'packages', 'displayCategories'));
}

    /**
     * Update the specified display menu
     */
    public function update(Request $request, DisplayMenu $displayMenu)
    {
        // Ensure user owns this menu
        if ($displayMenu->user_id !== auth()->id()) {
            abort(403);
        }

            
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0', // Changed from nullable to required
            'unit_type' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            // Handle image replacement
            $imagePath = $displayMenu->image_path;
            if ($request->hasFile('image')) {
                // Delete old image from Cloudinary if it exists
                if ($displayMenu->image_path) {
                    try {
                        $this->deleteFromCloudinary($displayMenu->image_path);
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete old image from Cloudinary', ['error' => $e->getMessage()]);
                    }
                }
                
                // Upload new image
                $imagePath = $this->uploadToCloudinary($request->file('image'), 'display_menus');
            }

            $displayMenu->update([
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description ?? '',
                'price' => $request->price,
                'unit_type' => $request->unit_type ?? 'item',
                'image_path' => $imagePath,
                'status' => $request->status,
            ]);

            return back()->with('success', 'Display menu updated successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to update display menu', [
                'menu_id' => $displayMenu->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to update display menu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified display menu
     */
    public function destroy(DisplayMenu $displayMenu)
    {
        // Ensure user owns this menu
        if ($displayMenu->user_id !== auth()->id()) {
            abort(403);
        }
        
        try {
            // Delete image from Cloudinary if it exists
            if ($displayMenu->image_path) {
                try {
                    $this->deleteFromCloudinary($displayMenu->image_path);
                } catch (\Exception $e) {
                    Log::warning('Failed to delete image from Cloudinary', ['error' => $e->getMessage()]);
                }
            }
            
            $displayMenu->delete();

            return back()->with('success', 'Display menu deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete display menu', [
                'menu_id' => $displayMenu->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to delete display menu.');
        }
    }

    /**
     * Toggle menu status (active/inactive)
     */
    public function toggleStatus(DisplayMenu $displayMenu)
    {
        // Ensure user owns this menu
        if ($displayMenu->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $newStatus = $displayMenu->status === 'active' ? 'inactive' : 'active';
            $displayMenu->update(['status' => $newStatus]);

            return back()->with('success', 'Menu status updated to ' . $newStatus . '!');
        } catch (\Exception $e) {
            Log::error('Failed to toggle menu status', [
                'menu_id' => $displayMenu->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to update status.');
        }
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
            throw new \Exception('Cloudinary credentials not configured. Please set CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, and CLOUDINARY_API_SECRET in your .env file.');
        }
        
        $timestamp = time();
        $publicId = $folder . '/' . uniqid();
        
        // Generate signature
        $signatureString = "folder={$folder}&public_id={$publicId}&timestamp={$timestamp}{$apiSecret}";
        $signature = sha1($signatureString);
        
        // Prepare the upload
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
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            Log::error('Cloudinary upload failed', [
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $error
            ]);
            throw new \Exception('Failed to upload image to Cloudinary (HTTP ' . $httpCode . ')');
        }
        
        $result = json_decode($response, true);
        
        if (!isset($result['secure_url'])) {
            throw new \Exception('Invalid response from Cloudinary');
        }
        
        return $result['secure_url'];
    }
    
    /**
     * Delete image from Cloudinary
     */
    private function deleteFromCloudinary($url)
    {
        if (empty($url) || strpos($url, 'cloudinary.com') === false) {
            return;
        }
        
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');
        
        if (!$cloudName || !$apiKey || !$apiSecret) {
            return;
        }
        
        // Extract public_id from URL
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
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            Log::warning('Failed to delete image from Cloudinary', [
                'public_id' => $publicId,
                'http_code' => $httpCode,
                'response' => $response
            ]);
        }
    }
    
    /**
     * Extract Cloudinary public_id from URL
     */
    private function getPublicIdFromUrl($url)
    {
        if (empty($url) || strpos($url, 'cloudinary.com') === false) {
            return null;
        }
        
        $parts = explode('/upload/', $url);
        if (count($parts) < 2) {
            return null;
        }
        
        $pathParts = explode('/', $parts[1]);
        array_shift($pathParts); // Remove version (v1234567)
        $publicId = implode('/', $pathParts);
        
        // Remove file extension
        $publicId = preg_replace('/\.[^.]+$/', '', $publicId);
        
        return $publicId;
    }
}