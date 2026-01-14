<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'caterer_id',
        'quantity',
        'event_date',
        'guest_count',
        'special_requests',
        'price',
    ];

    protected $casts = [
        'event_date' => 'date',
        'price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package for this cart item.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the caterer for this cart item.
     */
    public function caterer()
    {
        return $this->belongsTo(User::class, 'caterer_id');
    }

    /**
     * Get the subtotal for this cart item.
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}