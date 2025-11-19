<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Package;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function home()
    {
        return view('customer.dashboard');
    }

    public function caterers(Request $request)
    {
        // Get caterers with their packages and ratings
        $query = User::where('role', 'caterer')
            ->where('status', 'approved')
            ->with(['packages' => function($q) {
                $q->where('status', 'active');
            }]);

        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('business_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('business_address', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Apply location filter if provided
        if ($request->has('location') && !empty($request->location)) {
            $query->where('business_address', 'LIKE', '%' . $request->location . '%');
        }

        // Apply cuisine filter if provided
        if ($request->has('cuisine') && !empty($request->cuisine)) {
            $query->whereHas('packages', function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->cuisine . '%');
            });
        }

        $caterers = $query->paginate(12);

        // Add review statistics for each caterer
        foreach ($caterers as $caterer) {
            // For now, we'll use placeholder review data
            // You can replace this with actual review model queries later
            $caterer->review_count = rand(50, 300);
            $caterer->average_rating = round(rand(35, 50) / 10, 1);
        }

        return view('customer.caterers', compact('caterers'));
    }

    public function showCaterer($id)
    {
        $caterer = User::where('role', 'caterer')
            ->where('status', 'approved')
            ->where('id', $id)
            ->with(['packages' => function($q) {
                $q->where('status', 'active')->with('items.category');
            }])
            ->firstOrFail();

        // Add review statistics
        $caterer->review_count = rand(50, 300);
        $caterer->average_rating = round(rand(35, 50) / 10, 1);

        return view('customer.caterer-profile', compact('caterer'));
    }

    public function showPackage($catererId, $packageId)
    {
        $package = Package::where('id', $packageId)
            ->where('user_id', $catererId)
            ->where('status', 'active')
            ->with(['items.category', 'user'])
            ->firstOrFail();

        return view('customer.package-details', compact('package'));
    }

    /**
     * Calculate customized package price based on selected items
     */
    public function calculateCustomPrice(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:menu_items,id',
            'guests' => 'required|integer|min:1'
        ]);

        $menuItems = MenuItem::whereIn('id', $request->items)->get();
        
        // Calculate food cost
        $foodCost = $menuItems->sum('price');
        
        // Apply markups
        $laborAndUtilities = $foodCost * 0.20;
        $equipmentTransport = $foodCost * 0.10;
        $profitMargin = $foodCost * 0.25;
        
        $pricePerHead = $foodCost + $laborAndUtilities + $equipmentTransport + $profitMargin;
        $pricePerHead = round($pricePerHead / 5) * 5; // Round to nearest 5
        
        $totalPrice = $pricePerHead * $request->guests;

        return response()->json([
            'success' => true,
            'price_per_head' => $pricePerHead,
            'total_price' => $totalPrice,
            'breakdown' => [
                'food_cost' => $foodCost,
                'labor_utilities' => $laborAndUtilities,
                'equipment_transport' => $equipmentTransport,
                'profit_margin' => $profitMargin,
            ],
            'items' => $menuItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price
                ];
            })
        ]);
    }

    public function bookings()
    {
        return view('customer.bookings');
    }

    public function cart()
    {
        return view('customer.cart');
    }

    public function payments()
    {
        return view('customer.payments');
    }

    public function notifications()
    {
        return view('customer.notifications');
    }

    public function summary()
    {
        return view('customer.summary');
    }
}