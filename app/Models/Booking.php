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
        'payment_status',
        'booking_status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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

    public function getFormattedBookingNumberAttribute()
    {
        return strtoupper($this->booking_number);
    }

    public function getStatusColorAttribute()
    {
        return match($this->booking_status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'deposit_paid' => 'yellow',
            'fully_paid' => 'green',
            'refunded' => 'red',
            default => 'gray',
        };
    }
}