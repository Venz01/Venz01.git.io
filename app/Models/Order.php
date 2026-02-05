<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'caterer_id',
        'order_number',
        'fulfillment_type',
        'fulfillment_date',
        'fulfillment_time',
        'delivery_address',
        'special_instructions',
        'customer_name',
        'customer_email',
        'customer_phone',
        'subtotal',
        'delivery_fee',
        'total_amount',
        'payment_method',
        'receipt_path',
        'payment_status',
        'order_status',
        'cancellation_reason',
    ];

    protected $casts = [
        'fulfillment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the order
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the caterer that owns the order
     */
    public function caterer()
    {
        return $this->belongsTo(User::class, 'caterer_id');
    }

    /**
     * Get the order items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the display menu items through order items
     */
    public function displayMenus()
    {
        return $this->belongsToMany(DisplayMenu::class, 'order_items')
            ->withPivot('quantity', 'price', 'subtotal')
            ->withTimestamps();
    }

    /**
     * Get status color for badges
     */
    public function getStatusColorAttribute()
    {
        return match($this->order_status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'preparing' => 'purple',
            'ready' => 'indigo',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get payment status color
     */
    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'yellow',
            'paid' => 'green',
            'refunded' => 'red',
            default => 'gray',
        };
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->order_status, ['pending', 'confirmed']);
    }

    /**
     * Scope for specific customer
     */
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope for specific caterer
     */
    public function scopeForCaterer($query, $catererId)
    {
        return $query->where('caterer_id', $catererId);
    }
}