<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Package;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function home()
    {
        return view('customer.dashboard');
    }

/**
     * Browse packages — works for both guests and logged-in customers.
     * Used by both /browse/caterers (public) and /customer/caterers (auth).
     */
    public function browsePackages(Request $request)
    {
        $query = Package::with(['user', 'items'])
            ->where('status', 'active')
            ->whereHas('user', fn($u) => $u->where('status', 'approved'));

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) =>
                      $u->where('business_name', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                  );
            });
        }

        if ($eventType = $request->event_type) {
            $query->where('name', 'like', "%{$eventType}%");
        }

        if ($cuisine = $request->cuisine) {
            $query->whereHas('user', fn($u) =>
                $u->whereJsonContains('cuisine_types', $cuisine)
            );
        }

        if ($location = $request->location) {
            $query->whereHas('user', fn($u) =>
                $u->where('business_address', 'like', "%{$location}%")
                  ->orWhere('city', 'like', "%{$location}%")
            );
        }

        if ($minPrice = $request->min_price) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice = $request->max_price) {
            $query->where('price', '<=', $maxPrice);
        }

        match ($request->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest'     => $query->latest(),
            default      => $query->latest(),
        };

        $packages = $query->paginate(16)->withQueryString();

        $savedPreferences = [];
        if (auth()->check() && auth()->user()->dietary_preferences) {
            $savedPreferences = is_array(auth()->user()->dietary_preferences)
                ? auth()->user()->dietary_preferences
                : [];
        }

        return view('customer.packages', compact('packages', 'savedPreferences'));
    }


    public function showCaterer($id)
    {
        $caterer = User::where('role', 'caterer')
            ->where('status', 'approved')
            ->where('id', $id)
            ->with([
                'packages' => function($q) {
                    $q->where('status', 'active')->with('items.category');
                },
                'displayMenus' => function($q) {
                    $q->where('status', 'active')
                      ->orderBy('category')
                      ->orderBy('name');
                }
            ])
            ->firstOrFail();

        $caterer->review_count = rand(50, 300);
        $caterer->average_rating = round(rand(35, 50) / 10, 1);

        // Dietary preference sorting — only for logged-in customers
        $savedPreferences = [];
        if (auth()->check() && auth()->user()->isCustomer()) {
            $savedPreferences = is_array(auth()->user()->dietary_preferences)
                ? auth()->user()->dietary_preferences
                : [];
        }

        if (!empty($savedPreferences)) {
            $caterer->packages = $caterer->packages
                ->map(function ($package) use ($savedPreferences) {
                    $packageTags = is_array($package->dietary_tags)
                        ? $package->dietary_tags
                        : [];
                    $package->dietary_match_score = count(
                        array_intersect($savedPreferences, $packageTags)
                    );
                    return $package;
                })
                ->sortByDesc('dietary_match_score')
                ->values();
        } else {
            $caterer->packages = $caterer->packages->map(function ($package) {
                $package->dietary_match_score = 0;
                return $package;
            });
        }

        return view('customer.caterer-profile', compact('caterer', 'savedPreferences'));
    }

    /**
     * Show a single package's details — works for both guests and logged-in customers.
     */
    public function showPackage($catererId, $packageId)
    {
        $package = Package::where('id', $packageId)
            ->where('user_id', $catererId)
            ->where('status', 'active')
            ->with(['items.category', 'user', 'costing'])
            ->firstOrFail();

        return view('customer.package-details', compact('package'));
    }

    /**
     * Calculate customized package price based on selected items.
     */
    public function calculateCustomPrice(Request $request)
    {
        $request->validate([
            'items'    => 'required|array',
            'items.*'  => 'exists:menu_items,id',
            'guests'   => 'required|integer|min:1',
        ]);

        $menuItems = MenuItem::whereIn('id', $request->items)->get();

        $foodCost           = $menuItems->sum('price');
        $laborAndUtilities  = $foodCost * 0.20;
        $equipmentTransport = $foodCost * 0.10;
        $profitMargin       = $foodCost * 0.25;

        $pricePerHead = $foodCost + $laborAndUtilities + $equipmentTransport + $profitMargin;
        $pricePerHead = round($pricePerHead / 5) * 5;

        $totalPrice = $pricePerHead * $request->guests;

        return response()->json([
            'success'        => true,
            'price_per_head' => $pricePerHead,
            'total_price'    => $totalPrice,
            'breakdown'      => [
                'food_cost'           => $foodCost,
                'labor_utilities'     => $laborAndUtilities,
                'equipment_transport' => $equipmentTransport,
                'profit_margin'       => $profitMargin,
            ],
            'items' => $menuItems->map(fn($item) => [
                'id'    => $item->id,
                'name'  => $item->name,
                'price' => $item->price,
            ]),
        ]);
    }

    public function bookings(Request $request)
    {
        $query = \App\Models\Booking::where('customer_id', auth()->id())
            ->with(['caterer', 'package', 'menuItems'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('booking_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('booking_number', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhereHas('caterer', function($q) use ($searchTerm) {
                      $q->where('business_name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('name', 'LIKE', '%' . $searchTerm . '%');
                  });
            });
        }

        $bookings = $query->paginate(10);

        $stats = [
            'pending'   => \App\Models\Booking::where('customer_id', auth()->id())->where('booking_status', 'pending')->count(),
            'confirmed' => \App\Models\Booking::where('customer_id', auth()->id())->where('booking_status', 'confirmed')->count(),
            'completed' => \App\Models\Booking::where('customer_id', auth()->id())->where('booking_status', 'completed')->count(),
            'cancelled' => \App\Models\Booking::where('customer_id', auth()->id())->where('booking_status', 'cancelled')->count(),
        ];

        return view('customer.bookings', compact('bookings', 'stats'));
    }

    public function cart()
    {
        return view('customer.cart');
    }

    public function payments()
    {
        $customerId = auth()->id();

        $bookings = \App\Models\Booking::where('customer_id', $customerId)
            ->with(['caterer', 'package'])
            ->orderBy('created_at', 'desc')
            ->get();

        $orders = Order::where('customer_id', $customerId)
            ->with(['caterer', 'items.displayMenu'])
            ->orderBy('created_at', 'desc')
            ->get();

        $bookingTotalPaid      = $bookings->where('payment_status', 'fully_paid')->sum('total_price');
        $bookingTotalDeposits  = $bookings->where('payment_status', 'deposit_paid')->sum('deposit_paid');
        $bookingPendingBalance = $bookings->where('payment_status', 'deposit_paid')->sum('balance');

        $orderTotalPaid    = $orders->where('payment_status', 'paid')->sum('total_amount');
        $orderTotalPending = $orders->where('payment_status', 'pending')->sum('total_amount');

        $totalPaid      = $bookingTotalPaid + $orderTotalPaid;
        $totalDeposits  = $bookingTotalDeposits;
        $pendingBalance = $bookingPendingBalance + $orderTotalPending;

        return view('customer.payments', compact(
            'bookings',
            'orders',
            'bookingTotalPaid',
            'bookingTotalDeposits',
            'bookingPendingBalance',
            'orderTotalPaid',
            'orderTotalPending',
            'totalPaid',
            'totalDeposits',
            'pendingBalance'
        ));
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