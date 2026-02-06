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
        'admin_status',
        'flagged_reason',
        'admin_notes',
        'reviewed_by',
        'admin_reviewed_at',
        'caterer_warned',
        'caterer_warned_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'is_approved' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'admin_reviewed_at' => 'datetime',
        'caterer_warned' => 'boolean',
        'caterer_warned_at' => 'datetime',
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
     * Get the admin who reviewed this review
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
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
        return $query->where('is_approved', true)->where('admin_status', 'approved');
    }

    /**
     * Scope for flagged reviews
     */
    public function scopeFlagged($query)
    {
        return $query->where('admin_status', 'flagged');
    }

    /**
     * Scope for reviews under review
     */
    public function scopeUnderReview($query)
    {
        return $query->where('admin_status', 'under_review');
    }

    /**
     * Scope for removed reviews
     */
    public function scopeRemoved($query)
    {
        return $query->where('admin_status', 'removed');
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
     * Scope for reviews that need admin attention
     */
    public function scopeNeedsAttention($query)
    {
        return $query->whereIn('admin_status', ['flagged', 'under_review']);
    }

    /**
     * Scope for low-rated reviews (1-2 stars)
     */
    public function scopeLowRated($query)
    {
        return $query->whereIn('rating', [1, 2]);
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

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return [
            'approved' => 'green',
            'flagged' => 'red',
            'under_review' => 'yellow',
            'removed' => 'gray',
        ][$this->admin_status] ?? 'gray';
    }

    /**
     * Get status badge text
     */
    public function getStatusTextAttribute()
    {
        return [
            'approved' => 'Approved',
            'flagged' => 'Flagged',
            'under_review' => 'Under Review',
            'removed' => 'Removed',
        ][$this->admin_status] ?? 'Unknown';
    }

    /**
     * Check if review is visible to public
     */
    public function isVisible()
    {
        return $this->is_approved && $this->admin_status === 'approved';
    }

    /**
     * Check if review needs admin attention
     */
    public function needsAttention()
    {
        return in_array($this->admin_status, ['flagged', 'under_review']);
    }
}