<?php

namespace App\Http\Controllers;

use App\Models\DietaryTag;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DietaryTagController extends Controller
{
    /**
     * Display listing of dietary tags (for caterer management)
     */
    public function index()
    {
        $tags = DietaryTag::orderBy('is_system', 'desc')
                          ->orderBy('name')
                          ->get();

        return view('caterer.dietary-tags.index', compact('tags'));
    }

    /**
     * Store a new dietary tag
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:dietary_tags,name',
            'icon' => 'nullable|string|max:10',
            'color' => 'required|string|in:red,green,blue,yellow,purple,pink,indigo,orange,cyan,emerald,gray',
        ]);

        DietaryTag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?? '🏷️',
            'color' => $request->color,
            'is_system' => false, // Custom tags are never system tags
        ]);

        return redirect()->route('caterer.dietary-tags.index')
                         ->with('success', 'Dietary tag created successfully! It is now available for all customers.');
    }

    /**
     * Update an existing dietary tag
     */
    public function update(Request $request, DietaryTag $dietaryTag)
    {
        // Prevent editing system tags
        if ($dietaryTag->is_system) {
            return redirect()->back()
                           ->with('error', 'System tags cannot be modified.');
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:dietary_tags,name,' . $dietaryTag->id,
            'icon' => 'nullable|string|max:10',
            'color' => 'required|string|in:red,green,blue,yellow,purple,pink,indigo,orange,cyan,emerald,gray',
        ]);

        $dietaryTag->update([
            'name' => $request->name,
            'icon' => $request->icon ?? $dietaryTag->icon,
            'color' => $request->color,
        ]);

        return redirect()->route('caterer.dietary-tags.index')
                         ->with('success', 'Dietary tag updated successfully!');
    }

    /**
     * Delete a dietary tag
     */
    public function destroy(DietaryTag $dietaryTag)
    {
        // Prevent deletion of system tags
        if ($dietaryTag->is_system) {
            return redirect()->back()
                           ->with('error', 'System tags cannot be deleted.');
        }

        // Check if tag is in use
        $packagesCount = Package::whereJsonContains('dietary_tags', $dietaryTag->slug)->count();
        $usersCount = User::whereJsonContains('dietary_preferences', $dietaryTag->slug)->count();

        if ($packagesCount > 0 || $usersCount > 0) {
            return redirect()->back()
                           ->with('error', "Cannot delete tag. It is being used by {$packagesCount} package(s) and {$usersCount} customer(s).");
        }

        $dietaryTag->delete();

        return redirect()->route('caterer.dietary-tags.index')
                         ->with('success', 'Dietary tag deleted successfully!');
    }
}