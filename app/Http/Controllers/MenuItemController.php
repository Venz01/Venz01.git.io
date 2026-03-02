<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    use HandlesImageUploads;

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:available,unavailable',
        ]);

        try {
            $imagePath = $request->hasFile('image')
                ? $this->handleImageUpload($request->file('image'), 'menu-items')
                : null;

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

        } catch (\Exception $e) {
            \Log::error('MenuItem store failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to add menu item: ' . $e->getMessage());
        }
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
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:available,unavailable',
        ]);

        try {
            $imagePath = $item->image_path;
            if ($request->hasFile('image')) {
                $this->deleteImage($item->image_path);
                $imagePath = $this->handleImageUpload($request->file('image'), 'menu-items');
            }

            $item->update([
                'name'        => $request->name,
                'description' => $request->description ?? '',
                'price'       => $request->price,
                'image_path'  => $imagePath,
                'status'      => $request->status,
            ]);

            return back()->with('success', 'Menu item updated!');

        } catch (\Exception $e) {
            \Log::error('MenuItem update failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update menu item: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = MenuItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        try {
            $this->deleteImage($item->image_path);
            $item->delete();

            return back()->with('success', 'Menu item deleted!');

        } catch (\Exception $e) {
            \Log::error('MenuItem destroy failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to delete menu item.');
        }
    }
}