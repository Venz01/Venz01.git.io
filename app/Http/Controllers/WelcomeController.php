<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Package;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Support\Facades\Cache;

class WelcomeController extends Controller
{
    /**
     * Show the landing page with real data from database
     */
    public function index()
    {
        // Keep landing page fast under traffic spikes.
        $ttl = now()->addMinutes(10);

        // Get featured/top caterers (approved caterers with active packages)
        $featuredCaterers = Cache::remember('welcome:featured_caterers', $ttl, function () {
            return User::query()
                ->where('role', 'caterer')
                ->where('status', 'approved')
                ->withCount(['approvedReviews'])
                ->withAvg('approvedReviews as average_rating', 'rating')
                ->with([
                    'packages' => function ($q) {
                        $q->where('status', 'active')
                            ->select('id', 'user_id', 'price')
                            ->limit(1);
                    },
                    'featuredImages:id,user_id,image_path',
                ])
                ->has('packages')
                ->orderByDesc('approved_reviews_count')
                ->limit(6)
                ->get()
                ->each(function ($caterer) {
                    $caterer->total_reviews = $caterer->approved_reviews_count ?? 0;
                    $caterer->average_rating = round((float) ($caterer->average_rating ?? 0), 1);
                });
            })
        ;

        // Get popular packages (packages with most bookings)
        $popularPackages = Cache::remember('welcome:popular_packages', $ttl, function () {
            return Package::query()
                ->where('status', 'active')
                ->whereHas('user', function ($q) {
                    $q->where('role', 'caterer')
                        ->where('status', 'approved');
                })
                ->withCount('bookings')
                ->with([
                    'user' => function ($q) {
                        $q->select('id', 'name', 'business_name')
                            ->withCount(['approvedReviews'])
                            ->withAvg('approvedReviews as average_rating', 'rating');
                    },
                ])
                ->orderByDesc('bookings_count')
                ->limit(6)
                ->get()
                ->each(function ($package) {
                    $package->caterer_rating = round((float) ($package->user->average_rating ?? 0), 1);
                    $package->caterer_reviews = $package->user->approved_reviews_count ?? 0;
                });
        });

        // Get recent reviews (5-star reviews with comments)
        $recentReviews = Cache::remember('welcome:recent_reviews', $ttl, function () {
            return Review::query()
                ->with([
                    'customer:id,name',
                    'caterer:id,name,business_name',
                ])
                ->where('is_approved', true)
                ->where('rating', 5)
                ->whereNotNull('comment')
                ->where('comment', '!=', '')
                ->latest()
                ->limit(6)
                ->get();
        });

        // Get statistics
        $stats = Cache::remember('welcome:stats', $ttl, function () {
            return [
                'total_caterers' => User::where('role', 'caterer')
                    ->where('status', 'approved')
                    ->count(),
                'total_packages' => Package::where('status', 'active')
                    ->whereHas('user', function ($q) {
                        $q->where('status', 'approved');
                    })
                    ->count(),
                'total_bookings' => Booking::whereIn('booking_status', ['completed', 'confirmed'])
                    ->count(),
                'total_reviews' => Review::where('is_approved', true)->count(),
                'average_rating' => round(Review::where('is_approved', true)->avg('rating') ?? 0, 1),
            ];
        });

        // Get cuisine types (unique from caterers)
        $cuisineTypes = Cache::remember('welcome:cuisine_types', $ttl, function () {
            return User::query()
                ->where('role', 'caterer')
                ->where('status', 'approved')
                ->whereNotNull('cuisine_types')
                ->select('cuisine_types')
                ->limit(200)
                ->get()
                ->pluck('cuisine_types')
                ->flatten()
                ->filter()
                ->unique()
                ->take(8)
                ->values();
        });

        return view('welcome', compact(
            'featuredCaterers',
            'popularPackages',
            'recentReviews',
            'stats',
            'cuisineTypes'
        ));
    }
}