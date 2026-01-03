<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'caterer_id',
        'rating',
        'comment',
        'caterer_response',
        'responded_at',
        'is_approved',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'is_approved' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the booking that this review belongs to
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the customer who wrote the review
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the caterer being reviewed
     */
    public function caterer()
    {
        return $this->belongsTo(User::class, 'caterer_id');
    }

    /**
     * Check if the caterer has responded
     */
    public function hasResponse()
    {
        return !is_null($this->caterer_response);
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for reviews of a specific caterer
     */
    public function scopeForCaterer($query, $catererId)
    {
        return $query->where('caterer_id', $catererId);
    }

    /**
     * Scope for reviews by a specific customer
     */
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope for reviews with specific rating
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Get star display (filled and empty stars)
     */
    public function getStarDisplayAttribute()
    {
        $filled = str_repeat('★', $this->rating);
        $empty = str_repeat('☆', 5 - $this->rating);
        return $filled . $empty;
    }
}