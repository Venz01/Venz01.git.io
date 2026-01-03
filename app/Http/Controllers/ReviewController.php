<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Show the form for creating a review
     */
    public function create($bookingId)
    {
        $booking = Booking::with(['caterer', 'package', 'menuItems'])
            ->where('customer_id', auth()->id())
            ->where('booking_status', 'completed')
            ->findOrFail($bookingId);

        // Check if already reviewed
        if ($booking->review()->exists()) {
            return redirect()->route('customer.bookings')
                ->with('error', 'You have already reviewed this booking.');
        }

        return view('customer.review-create', compact('booking'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request, $bookingId)
    {
        $booking = Booking::where('customer_id', auth()->id())
            ->where('booking_status', 'completed')
            ->findOrFail($bookingId);

        // Check if already reviewed
        if ($booking->review()->exists()) {
            return back()->with('error', 'You have already reviewed this booking.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'Please select a rating.',
            'rating.min' => 'Rating must be between 1 and 5 stars.',
            'rating.max' => 'Rating must be between 1 and 5 stars.',
            'comment.required' => 'Please write a review comment.',
            'comment.min' => 'Review must be at least 10 characters.',
            'comment.max' => 'Review must not exceed 1000 characters.',
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'customer_id' => auth()->id(),
            'caterer_id' => $booking->caterer_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true, // Auto-approve for now
        ]);

        return redirect()->route('customer.booking.details', $booking->id)
            ->with('success', 'Thank you for your review! Your feedback helps other customers.');
    }

    /**
     * Display reviews for a caterer (customer view)
     */
    public function index($catererId)
    {
        $reviews = Review::with(['customer', 'booking'])
            ->forCaterer($catererId)
            ->approved()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $caterer = \App\Models\User::findOrFail($catererId);

        // Calculate statistics
        $stats = [
            'total' => $reviews->total(),
            'average' => Review::forCaterer($catererId)->approved()->avg('rating') ?? 0,
            'distribution' => [
                5 => Review::forCaterer($catererId)->approved()->withRating(5)->count(),
                4 => Review::forCaterer($catererId)->approved()->withRating(4)->count(),
                3 => Review::forCaterer($catererId)->approved()->withRating(3)->count(),
                2 => Review::forCaterer($catererId)->approved()->withRating(2)->count(),
                1 => Review::forCaterer($catererId)->approved()->withRating(1)->count(),
            ],
        ];

        return view('customer.reviews-view', compact('reviews', 'caterer', 'stats'));
    }

    /**
     * Show caterer's own reviews (caterer dashboard)
     */
    public function catererReviews()
    {
        $reviews = Review::with(['customer', 'booking'])
            ->forCaterer(auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate statistics
        $stats = [
            'total' => Review::forCaterer(auth()->id())->count(),
            'average' => Review::forCaterer(auth()->id())->avg('rating') ?? 0,
            'pending_response' => Review::forCaterer(auth()->id())
                ->whereNull('caterer_response')
                ->count(),
            'distribution' => [
                5 => Review::forCaterer(auth()->id())->withRating(5)->count(),
                4 => Review::forCaterer(auth()->id())->withRating(4)->count(),
                3 => Review::forCaterer(auth()->id())->withRating(3)->count(),
                2 => Review::forCaterer(auth()->id())->withRating(2)->count(),
                1 => Review::forCaterer(auth()->id())->withRating(1)->count(),
            ],
        ];

        return view('caterer.reviews', compact('reviews', 'stats'));
    }

    /**
     * Caterer responds to a review
     */
    public function respond(Request $request, Review $review)
    {
        // Ensure caterer owns this review
        if ($review->caterer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'response' => 'required|string|min:10|max:1000',
        ], [
            'response.required' => 'Please write a response.',
            'response.min' => 'Response must be at least 10 characters.',
            'response.max' => 'Response must not exceed 1000 characters.',
        ]);

        $review->update([
            'caterer_response' => $request->response,
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Your response has been posted successfully!');
    }

    /**
     * Update caterer's response
     */
    public function updateResponse(Request $request, Review $review)
    {
        // Ensure caterer owns this review
        if ($review->caterer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'response' => 'required|string|min:10|max:1000',
        ]);

        $review->update([
            'caterer_response' => $request->response,
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Your response has been updated successfully!');
    }

    /**
     * Delete caterer's response
     */
    public function deleteResponse(Review $review)
    {
        // Ensure caterer owns this review
        if ($review->caterer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $review->update([
            'caterer_response' => null,
            'responded_at' => null,
        ]);

        return back()->with('success', 'Your response has been removed.');
    }
}