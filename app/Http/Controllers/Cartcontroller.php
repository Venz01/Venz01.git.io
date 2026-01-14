<?php

namespace App\Http\Controllers;  // ✅ FIXED NAMESPACE (was Customer\CartController)

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cartItems = CartItem::with(['package', 'caterer'])
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $cartItems->sum('subtotal');
        $tax = $subtotal * 0.12; // 12% tax (adjust as needed)
        $total = $subtotal + $tax;

        return view('customer.cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Add item to cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'caterer_id' => 'required|exists:users,id',
            'event_date' => 'nullable|date|after:today',
            'guest_count' => 'nullable|integer|min:1',
            'special_requests' => 'nullable|string|max:500',
        ]);

        $package = Package::findOrFail($request->package_id);

        // Check if item already exists in cart
        $existingItem = CartItem::where('user_id', Auth::id())
            ->where('package_id', $request->package_id)
            ->first();

        if ($existingItem) {
            return back()->with('info', 'This package is already in your cart. You can update it from the cart page.');
        }

        // Add to cart
        CartItem::create([
            'user_id' => Auth::id(),
            'package_id' => $request->package_id,
            'caterer_id' => $request->caterer_id,
            'quantity' => 1,
            'event_date' => $request->event_date,
            'guest_count' => $request->guest_count,
            'special_requests' => $request->special_requests,
            'price' => $package->price,
        ]);

        return back()->with('success', 'Package added to cart successfully!');
    }

    /**
     * Update cart item.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        // Ensure user owns this cart item
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
            'event_date' => 'nullable|date|after:today',
            'guest_count' => 'nullable|integer|min:1',
            'special_requests' => 'nullable|string|max:500',
        ]);

        $cartItem->update($request->only(['quantity', 'event_date', 'guest_count', 'special_requests']));

        return back()->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove item from cart.
     */
    public function destroy(CartItem $cartItem)
    {
        // Ensure user owns this cart item
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Clear entire cart.
     */
    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Cart cleared successfully!');
    }

    /**
     * Get cart count for navigation badge.
     */
    public function count()
    {
        $count = CartItem::where('user_id', Auth::id())->count();
        return response()->json(['count' => $count]);
    }
}