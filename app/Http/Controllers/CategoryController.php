<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        Category::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
        ]);

        return back()->with('success', 'Category added successfully!');
    }

    public function destroy($id)
    {
        $category = Category::where('id', $id)
            ->where('user_id', auth()->id()) // security check
            ->firstOrFail();

        // Optionally delete all menu items under this category
        $category->items()->delete();

        $category->delete();

        return back()->with('success', 'Category deleted successfully!');
    }

    public function update(Request $request, $id)
    {
        $category = Category::where('id', $id)
            ->where('user_id', auth()->id()) // make sure only the owner can edit
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description ?? '',
        ]);

        return back()->with('success', 'Category updated successfully!');
    }

}
