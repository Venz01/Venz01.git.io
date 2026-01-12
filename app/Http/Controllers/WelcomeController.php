<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Package;
use App\Models\Review;
use App\Models\Booking;

class WelcomeController extends Controller
{
    /**
     * Show the landing page with real data from database
     */
    public function index()
    {
        // Get featured/top caterers (approved caterers with active packages)
        $featuredCaterers = User::where('role', 'caterer')
            ->where('status', 'approved')
            ->withCount(['approvedReviews'])
            ->with(['packages' => function($q) {
                $q->where('status', 'active')->limit(1);
            }, 'featuredImages'])
            ->has('packages')
            ->orderBy('approved_reviews_count', 'desc')
            ->limit(6)
            ->get()
            ->map(function($caterer) {
                $caterer->average_rating = $caterer->averageRating();
                $caterer->total_reviews = $caterer->totalReviews();
                return $caterer;
            });

        // Get popular packages (packages with most bookings)
        $popularPackages = Package::where('status', 'active')
            ->whereHas('user', function($q) {
                $q->where('role', 'caterer')
                  ->where('status', 'approved');
            })
            ->with(['user', 'items.category'])
            ->withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(6)
            ->get()
            ->map(function($package) {
                // Get caterer's rating
                $package->caterer_rating = $package->user->averageRating();
                $package->caterer_reviews = $package->user->totalReviews();
                return $package;
            });

        // Get recent reviews (5-star reviews with comments)
        $recentReviews = Review::with(['customer', 'caterer', 'booking'])
            ->where('is_approved', true)
            ->where('rating', 5)
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get statistics
        $stats = [
            'total_caterers' => User::where('role', 'caterer')
                ->where('status', 'approved')
                ->count(),
            'total_packages' => Package::where('status', 'active')
                ->whereHas('user', function($q) {
                    $q->where('status', 'approved');
                })
                ->count(),
            'total_bookings' => Booking::whereIn('booking_status', ['completed', 'confirmed'])
                ->count(),
            'total_reviews' => Review::where('is_approved', true)->count(),
            'average_rating' => round(Review::where('is_approved', true)->avg('rating') ?? 0, 1),
        ];

        // Get cuisine types (unique from caterers)
        $cuisineTypes = User::where('role', 'caterer')
            ->where('status', 'approved')
            ->whereNotNull('cuisine_types')
            ->get()
            ->pluck('cuisine_types')
            ->flatten()
            ->unique()
            ->take(8)
            ->values();

        return view('welcome', compact(
            'featuredCaterers',
            'popularPackages',
            'recentReviews',
            'stats',
            'cuisineTypes'
        ));
    }
}