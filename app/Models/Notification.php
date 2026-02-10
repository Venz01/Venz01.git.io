<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Check if notification is read
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if notification is unread
     */
    public function isUnread()
    {
        return is_null($this->read_at);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope for recent notifications
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Get icon based on notification type
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            // Bookings
            'booking_created', 'booking_pending'          => 'clock',
            'booking_confirmed'                           => 'check-circle',
            'booking_rejected', 'booking_cancelled'       => 'x-circle',
            'booking_completed'                           => 'check',
            // Orders
            'order_placed'                                => 'shopping-cart',
            'order_confirmed'                             => 'check-circle',
            'order_preparing'                             => 'fire',
            'order_ready'                                 => 'bell',
            'order_completed'                             => 'check',
            'order_cancelled', 'order_update'             => 'x-circle',
            // Payments
            'payment_received'                            => 'credit-card',
            'balance_due'                                 => 'alert-circle',
            // Reviews
            'review_received'                             => 'star',
            'review_response'                             => 'message-circle',
            default                                       => 'bell',
        };
    }

    /**
     * Get color based on notification type
     */
    public function getColorAttribute()
    {
        return match($this->type) {
            'booking_confirmed', 'booking_completed',
            'order_confirmed', 'order_completed',
            'payment_received'                            => 'green',

            'booking_pending', 'balance_due',
            'order_placed', 'order_preparing'             => 'yellow',

            'booking_rejected', 'booking_cancelled',
            'order_cancelled'                             => 'red',

            'order_ready'                                 => 'indigo',
            'review_received', 'review_response'          => 'blue',
            default                                       => 'gray',
        };
    }
}