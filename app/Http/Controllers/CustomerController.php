<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Package;
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