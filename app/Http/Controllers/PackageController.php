<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'pax'         => 'required|integer|min:1',
            'menu_items'  => 'required|array|min:1',
            'menu_items.*'=> 'exists:menu_items,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('packages', 'public');
        }

        $package = Package::create([
            'user_id'     => auth()->id(),
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'pax'         => $request->pax,
            'status'      => 'active',
            'image_path'  => $imagePath,
        ]);

        $package->items()->attach($request->menu_items);

        return back()->with('success', 'Package created successfully!');
    }

    public function update(Request $request, $id)
    {
        $package = Package::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'pax'         => 'required|integer|min:1',
            'menu_items'  => 'required|array|min:1',
            'menu_items.*'=> 'exists:menu_items,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $package->image_path;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('packages', 'public');
        }

        $package->update([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'pax'         => $request->pax,
            'image_path'  => $imagePath,
        ]);

        $package->items()->sync($request->menu_items);

        return back()->with('success', 'Package updated successfully!');
    }


    public function destroy($id)
    {
        $package = Package::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $package->items()->detach();
        $package->delete();

        return back()->with('success', 'Package deleted successfully!');
    }

    public function toggle($id)
    {
        $package = Package::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $package->status = $package->status === 'active' ? 'inactive' : 'active';
        $package->save();

        return back()->with('success', 'Package status updated!');
    }

}
