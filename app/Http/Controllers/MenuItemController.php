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

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu_items', 'public');
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
            $imagePath = $request->file('image')->store('menu_items', 'public');
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
        $item->delete();

        return back()->with('success', 'Menu item deleted!');
    }
}
