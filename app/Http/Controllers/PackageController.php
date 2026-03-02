<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Models\Package;
use App\Models\MenuItem;
use App\Models\PackageCosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    use HandlesImageUploads;

    // ── Price Calculation ─────────────────────────────────────────────────────

    private function calculatePackagePrice(array $menuItemIds): float
    {
        $foodCost           = MenuItem::whereIn('id', $menuItemIds)->sum('price');
        $laborAndUtilities  = $foodCost * 0.20;
        $equipmentTransport = $foodCost * 0.10;
        $profitMargin       = $foodCost * 0.25;
        $totalPrice         = $foodCost + $laborAndUtilities + $equipmentTransport + $profitMargin;

        return round($totalPrice / 5) * 5;
    }

    // ── Store (Create) ────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'pax'            => 'required|integer|min:1|max:1000',
            'menu_items'     => 'required|array|min:1',
            'menu_items.*'   => [
                'exists:menu_items,id',
                function ($attribute, $value, $fail) {
                    $menuItem = MenuItem::find($value);
                    if (!$menuItem || $menuItem->user_id !== auth()->id()) {
                        $fail('One or more selected menu items are invalid.');
                    }
                },
            ],
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'dietary_tags'   => 'nullable|array',
            'dietary_tags.*' => 'string|in:no_pork,vegetarian,vegan,halal,gluten_free,dairy_free,seafood_free',
        ]);

        try {
            $calculatedPrice = $this->calculatePackagePrice($request->menu_items);
            $imagePath       = $request->hasFile('image')
                ? $this->handleImageUpload($request->file('image'), 'packages')
                : null;

            $package = Package::create([
                'user_id'      => auth()->id(),
                'name'         => $request->name,
                'description'  => $request->description ?? '',
                'price'        => $calculatedPrice,
                'pax'          => $request->pax,
                'status'       => 'active',
                'image_path'   => $imagePath,
                'dietary_tags' => $request->input('dietary_tags', []),
            ]);

            $package->items()->attach($request->menu_items);

            $this->applyDefaultCostingTemplate($package);

            return back()->with('success',
                'Package created successfully! Price per head: ₱' . number_format($calculatedPrice, 2));

        } catch (\Exception $e) {
            \Log::error('Package store failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to create package: ' . $e->getMessage());
        }
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, Package $package)
    {
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'pax'            => 'required|integer|min:1|max:1000',
            'menu_items'     => 'nullable|array',
            'menu_items.*'   => [
                'exists:menu_items,id',
                function ($attribute, $value, $fail) {
                    $menuItem = MenuItem::find($value);
                    if (!$menuItem || $menuItem->user_id !== auth()->id()) {
                        $fail('One or more selected menu items are invalid.');
                    }
                },
            ],
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'dietary_tags'   => 'nullable|array',
            'dietary_tags.*' => 'string|in:no_pork,vegetarian,vegan,halal,gluten_free,dairy_free,seafood_free',
        ]);

        try {
            $menuItems       = $request->input('menu_items', []);
            $calculatedPrice = count($menuItems) > 0
                ? $this->calculatePackagePrice($menuItems)
                : 0;

            $imagePath = $package->image_path;
            if ($request->hasFile('image')) {
                $this->deleteImage($package->image_path);
                $imagePath = $this->handleImageUpload($request->file('image'), 'packages');
            }

            $package->update([
                'name'         => $request->name,
                'description'  => $request->description ?? '',
                'price'        => $calculatedPrice,
                'pax'          => $request->pax,
                'image_path'   => $imagePath,
                'dietary_tags' => $request->input('dietary_tags', []),
            ]);

            $package->items()->sync($menuItems);

            return back()->with('success',
                'Package updated successfully! New price per head: ₱' . number_format($calculatedPrice, 2));

        } catch (\Exception $e) {
            \Log::error('Package update failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update package: ' . $e->getMessage());
        }
    }

    // ── Destroy ───────────────────────────────────────────────────────────────

    public function destroy(Package $package)
    {
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $this->deleteImage($package->image_path);
            $package->items()->detach();
            $package->delete();

            return back()->with('success', 'Package deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Package destroy failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to delete package: ' . $e->getMessage());
        }
    }

    // ── Toggle Status ─────────────────────────────────────────────────────────

    public function toggle(Package $package)
    {
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $package->status = $package->status === 'active' ? 'inactive' : 'active';
            $package->save();

            $statusText = $package->status === 'active' ? 'activated' : 'deactivated';
            return back()->with('success', "Package {$statusText} successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update package status.');
        }
    }

    // ── Get Items (AJAX) ──────────────────────────────────────────────────────

    public function getItems(Package $package)
    {
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json([
            'items' => $package->items()->pluck('menu_items.id')->toArray(),
        ]);
    }

    // ── Get Price Breakdown (AJAX — used by edit package modal) ───────────────

    public function getPriceBreakdown(Package $package)
    {
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $costing = $package->costing;

        if ($costing && $costing->total_cost > 0) {
            $breakdown = [
                'ingredient_cost'       => (float) $costing->ingredient_cost,
                'labor_cost'            => (float) $costing->labor_cost,
                'equipment_cost'        => (float) $costing->equipment_cost,
                'consumables_cost'      => (float) $costing->consumables_cost,
                'overhead_cost'         => (float) $costing->overhead_cost,
                'transport_cost'        => (float) $costing->transport_cost,
                'total_cost'            => $costing->total_cost,
                'profit_margin'         => $costing->profit_amount,
                'profit_margin_percent' => (float) $costing->profit_margin_percent,
                'total_per_head'        => $package->price,
                'total_package'         => $package->price * $package->pax,
                'has_costing'           => true,
            ];
        } else {
            $foodCost           = $package->items()->sum('price');
            $laborAndUtilities  = $foodCost * 0.20;
            $equipmentTransport = $foodCost * 0.10;
            $profitMargin       = $foodCost * 0.25;

            $breakdown = [
                'food_cost'           => $foodCost,
                'labor_utilities'     => $laborAndUtilities,
                'equipment_transport' => $equipmentTransport,
                'profit_margin'       => $profitMargin,
                'total_per_head'      => $package->price,
                'total_package'       => $package->price * $package->pax,
                'has_costing'         => false,
            ];
        }

        $breakdown['items'] = $package->items->map(fn ($item) => [
            'name'  => $item->name,
            'price' => $item->price,
        ]);

        return response()->json($breakdown);
    }

    // ── Default Costing Template ──────────────────────────────────────────────

    private function applyDefaultCostingTemplate(Package $package): void
    {
        $template = PackageCosting::getDefaultForCaterer(auth()->id());

        if (!$template) {
            return;
        }

        try {
            $newCosting = new PackageCosting([
                'package_id' => $package->id,
                'user_id'    => auth()->id(),
            ]);

            $template->applyTo($newCosting);

            $totalCost      = $newCosting->total_cost;
            $profitAmount   = $totalCost * ($newCosting->profit_margin_percent / 100);
            $suggestedPrice = $totalCost > 0 ? ceil(($totalCost + $profitAmount) / 5) * 5 : 0;

            $newCosting->suggested_price     = $suggestedPrice;
            $newCosting->is_default_template = false;
            $newCosting->save();

            \Log::info('Default costing template applied to new package', [
                'package_id'  => $package->id,
                'template_id' => $template->id,
            ]);

        } catch (\Exception $e) {
            \Log::warning('Failed to apply default costing template', [
                'package_id' => $package->id,
                'error'      => $e->getMessage(),
            ]);
        }
    }
}