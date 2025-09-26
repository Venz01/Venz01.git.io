<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,NULL,id,user_id,' . auth()->id()
            ],
            'description' => 'nullable|string|max:1000',
        ], [
            'name.unique' => 'You already have a category with this name.',
        ]);

        try {
            Category::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description ?? '',
            ]);

            return back()->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create category. Please try again.');
        }
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        // Ensure user owns this category
        if ($category->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,' . $category->id . ',id,user_id,' . auth()->id()
            ],
            'description' => 'nullable|string|max:1000',
        ], [
            'name.unique' => 'You already have a category with this name.',
        ]);

        try {
            $category->update([
                'name' => $request->name,
                'description' => $request->description ?? '',
            ]);

            return back()->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update category. Please try again.');
        }
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        // Ensure user owns this category
        if ($category->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if category has menu items
        if ($category->items()->count() > 0) {
            return back()->with('error', 'Cannot delete category that contains menu items. Delete the items first.');
        }

        try {
            $category->delete();
            return back()->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete category. Please try again.');
        }
    }
}