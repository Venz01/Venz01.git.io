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
            $caterer->review_count = rand(50, 300);
            $caterer->average_rating = round(rand(35, 50) / 10, 1);
        }

        // ── Dietary preference sorting ──────────────────────────────────────
        // Get the logged-in customer's saved dietary preferences
        $savedPreferences = [];
        if (auth()->check() && auth()->user()->isCustomer()) {
            $savedPreferences = is_array(auth()->user()->dietary_preferences)
                ? auth()->user()->dietary_preferences
                : [];
        }

        if (!empty($savedPreferences)) {
            // Score each caterer: count how many of the customer's prefs
            // appear in ANY of that caterer's active package dietary_tags
            $caterers->getCollection()->transform(function ($caterer) use ($savedPreferences) {
                $catererTags = $caterer->packages
                    ->flatMap(fn($p) => is_array($p->dietary_tags) ? $p->dietary_tags : [])
                    ->unique()
                    ->values()
                    ->toArray();

                $caterer->dietary_match_score = count(
                    array_intersect($savedPreferences, $catererTags)
                );

                return $caterer;
            });

            // Sort: highest match score first, then keep original order for ties
            $sorted = $caterers->getCollection()
                ->sortByDesc('dietary_match_score')
                ->values();

            $caterers->setCollection($sorted);
        }
        // ───────────────────────────────────────────────────────────────────

        return view('customer.caterers', compact('caterers', 'savedPreferences'));
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

        // Add review statistics
        $caterer->review_count = rand(50, 300);
        $caterer->average_rating = round(rand(35, 50) / 10, 1);

        // ── Dietary preference sorting for packages ─────────────────────────
        $savedPreferences = [];
        if (auth()->check() && auth()->user()->isCustomer()) {
            $savedPreferences = is_array(auth()->user()->dietary_preferences)
                ? auth()->user()->dietary_preferences
                : [];
        }

        if (!empty($savedPreferences)) {
            // Score each package: count matching prefs, then sort desc
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
            // No prefs — add a zero score so the view can check it uniformly
            $caterer->packages = $caterer->packages->map(function ($package) {
                $package->dietary_match_score = 0;
                return $package;
            });
        }
        // ───────────────────────────────────────────────────────────────────

        return view('customer.caterer-profile', compact('caterer', 'savedPreferences'));
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
        
        $foodCost = $menuItems->sum('price');
        $laborAndUtilities = $foodCost * 0.20;
        $equipmentTransport = $foodCost * 0.10;
        $profitMargin = $foodCost * 0.25;
        
        $pricePerHead = $foodCost + $laborAndUtilities + $equipmentTransport + $profitMargin;
        $pricePerHead = round($pricePerHead / 5) * 5;
        
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