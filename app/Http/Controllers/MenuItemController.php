<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
            $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'menu_items'
            ]);
            $imagePath = $uploadedFile->getSecurePath();
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
            ->where('user_id', auth()->id()) // ensure only owner can edit
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
                    // Extract public_id from URL and delete
                    $publicId = $this->getPublicIdFromUrl($item->image_path);
                    if ($publicId) {
                        Cloudinary::destroy($publicId);
                    }
                } catch (\Exception $e) {
                    // Continue even if deletion fails
                }
            }
            
            // Upload new image
            $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'menu_items'
            ]);
            $imagePath = $uploadedFile->getSecurePath();
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
                $publicId = $this->getPublicIdFromUrl($item->image_path);
                if ($publicId) {
                    Cloudinary::destroy($publicId);
                }
            } catch (\Exception $e) {
                // Continue even if deletion fails
            }
        }
        
        $item->delete();

        return back()->with('success', 'Menu item deleted!');
    }
    
    /**
     * Extract Cloudinary public_id from URL
     * Example: https://res.cloudinary.com/cloud/image/upload/v123/menu_items/abc.jpg
     * Returns: menu_items/abc
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
        // Remove version (v1234567890) and get folder/filename
        array_shift($pathParts); // Remove version
        $publicId = implode('/', $pathParts);
        
        // Remove file extension
        $publicId = preg_replace('/\.[^.]+$/', '', $publicId);
        
        return $publicId;
    }
}