<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'caterer_id',
        'package_id',
        'booking_number',
        'event_type',
        'event_date',
        'time_slot',
        'guests',
        'venue_name',
        'venue_address',
        'special_instructions',
        'price_per_head',
        'total_price',
        'deposit_amount',
        'service_fee',
        'deposit_paid',
        'balance',
        'customer_name',
        'customer_email',
        'customer_phone',
        'payment_method',
        'receipt_path',
        'balance_receipt_path',
        'payment_status',
        'booking_status',
        // Cancellation fields
        'cancelled_by',
        'cancellation_reason',
        'refund_status',
        'refund_details',
        'cancelled_at',
    ];

    protected $casts = [
        'event_date'   => 'date',
        'cancelled_at' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function caterer()
    {
        return $this->belongsTo(User::class, 'caterer_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'booking_menu_items', 'booking_id', 'menu_item_id')
                    ->withTimestamps();
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // ── Accessors ──────────────────────────────────────────────────────

    public function getFormattedBookingNumberAttribute()
    {
        return strtoupper($this->booking_number);
    }

    public function getStatusColorAttribute()
    {
        return match($this->booking_status) {
            'pending'   => 'yellow',
            'confirmed' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'deposit_paid' => 'yellow',
            'fully_paid'   => 'green',
            'refunded'     => 'red',
            default        => 'gray',
        };
    }

    // ── Business logic ─────────────────────────────────────────────────

    /**
     * Customer may only cancel while the booking is still PENDING
     * (caterer has not yet confirmed it).
     */
    public function canBeCancelledByCustomer(): bool
    {
        return $this->booking_status === 'pending';
    }

    /**
     * Caterer may cancel a pending or confirmed booking
     * whose event date is still in the future.
     */
    public function canBeCancelledByCaterer(): bool
    {
        return in_array($this->booking_status, ['pending', 'confirmed'])
            && $this->event_date->isFuture();
    }

    /**
     * True when money was paid and a refund decision is still outstanding.
     */
    public function needsRefundTracking(): bool
    {
        return $this->booking_status === 'cancelled'
            && $this->hasRefundablePayment()
            && in_array($this->refund_status ?? 'none', ['none', 'pending']);
    }

    /**
     * Refundable principal excludes service fee.
     * For now, we treat paid deposit_amount as refundable basis.
     */
    public function refundableBaseAmount(): float
    {
        return (float) ($this->deposit_amount ?? 0);
    }

    public function hasRefundablePayment(): bool
    {
        return $this->refundableBaseAmount() > 0;
    }

    /**
     * Returns true when the given customer is allowed to leave a review
     * for this booking. Pass auth()->id() from the controller/view.
     */
    public function canBeReviewed(int $customerId): bool
    {
        return $this->booking_status === 'completed'
            && ! $this->review()->exists()
            && $this->customer_id === $customerId;
    }

    public function hasReview(): bool
    {
        return $this->review()->exists();
    }

    public function getReviewRatingAttribute()
    {
        return $this->review ? $this->review->rating : null;
    }
}