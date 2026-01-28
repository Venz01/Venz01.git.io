<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;

class MenuItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'      => 'required|in:available,unavailable',
        ]);

        // Handle image upload to Cloudinary
        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->uploadToCloudinary($request->file('image'), 'menu_items');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }
        }

        MenuItem::create([
            'category_id' => $request->category_id,
            'user_id'     => auth()->id(),
            'name'        => $request->name,
            'description' => $request->description ?? '',
            'price'       => $request->price,
            'image_path'  => $imagePath,
            'status'      => $request->status,
        ]);

        return back()->with('success', 'Menu item added successfully!');
    }

    public function update(Request $request, $id)
    {
        $item = MenuItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'      => 'required|in:available,unavailable',
        ]);

        // Handle image replacement
        $imagePath = $item->image_path;
        if ($request->hasFile('image')) {
            // Delete old image from Cloudinary if it exists
            if ($item->image_path) {
                try {
                    $this->deleteFromCloudinary($item->image_path);
                } catch (\Exception $e) {
                    // Continue even if deletion fails
                }
            }
            
            // Upload new image
            try {
                $imagePath = $this->uploadToCloudinary($request->file('image'), 'menu_items');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }
        }

        $item->update([
            'name'        => $request->name,
            'description' => $request->description ?? '',
            'price'       => $request->price,
            'image_path'  => $imagePath,
            'status'      => $request->status,
        ]);

        return back()->with('success', 'Menu item updated!');
    }

    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);
        
        // Delete image from Cloudinary if it exists
        if ($item->image_path) {
            try {
                $this->deleteFromCloudinary($item->image_path);
            } catch (\Exception $e) {
                // Continue even if deletion fails
            }
        }
        
        $item->delete();

        return back()->with('success', 'Menu item deleted!');
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
        array_shift($pathParts); // Remove version
        $publicId = implode('/', $pathParts);
        $publicId = preg_replace('/\.[^.]+$/', '', $publicId);
        
        return $publicId;
    }
}