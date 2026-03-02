<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Models\DisplayMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DisplayMenuController extends Controller
{
    use HandlesImageUploads;

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'unit_type'   => 'nullable|string|max:50',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:active,inactive',
        ]);

        try {
            $imagePath = $request->hasFile('image')
                ? $this->handleImageUpload($request->file('image'), 'display-menus')
                : null;

            DisplayMenu::create([
                'user_id'     => auth()->id(),
                'name'        => $request->name,
                'category'    => $request->category,
                'description' => $request->description ?? '',
                'price'       => $request->price,
                'unit_type'   => $request->unit_type ?? 'item',
                'image_path'  => $imagePath,
                'status'      => $request->status,
            ]);

            return back()->with('success', 'Display menu added successfully!');

        } catch (\Exception $e) {
            Log::error('DisplayMenu store failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to add display menu: ' . $e->getMessage());
        }
    }

    public function update(Request $request, DisplayMenu $displayMenu)
    {
        if ($displayMenu->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'unit_type'   => 'nullable|string|max:50',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:active,inactive',
        ]);

        try {
            $imagePath = $displayMenu->image_path;
            if ($request->hasFile('image')) {
                $this->deleteImage($displayMenu->image_path);
                $imagePath = $this->handleImageUpload($request->file('image'), 'display-menus');
            }

            $displayMenu->update([
                'name'        => $request->name,
                'category'    => $request->category,
                'description' => $request->description ?? '',
                'price'       => $request->price,
                'unit_type'   => $request->unit_type ?? 'item',
                'image_path'  => $imagePath,
                'status'      => $request->status,
            ]);

            return back()->with('success', 'Display menu updated successfully!');

        } catch (\Exception $e) {
            Log::error('DisplayMenu update failed', ['menu_id' => $displayMenu->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update display menu: ' . $e->getMessage());
        }
    }

    public function destroy(DisplayMenu $displayMenu)
    {
        if ($displayMenu->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $this->deleteImage($displayMenu->image_path);
            $displayMenu->delete();

            return back()->with('success', 'Display menu deleted successfully!');

        } catch (\Exception $e) {
            Log::error('DisplayMenu destroy failed', ['menu_id' => $displayMenu->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to delete display menu.');
        }
    }

    public function toggleStatus(DisplayMenu $displayMenu)
    {
        if ($displayMenu->user_id !== auth()->id()) {
            abort(403);
        }

        $displayMenu->status = $displayMenu->status === 'active' ? 'inactive' : 'active';
        $displayMenu->save();

        return back()->with('success', 'Menu status updated to ' . $displayMenu->status . '!');
    }
}