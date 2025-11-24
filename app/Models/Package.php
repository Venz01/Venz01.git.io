<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'price', 'pax', 'status', 'image_path'
    ];

    public function items()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_package', 'package_id', 'menu_item_id')
                    ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all bookings for this package
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get confirmed bookings for this package
     */
    public function confirmedBookings()
    {
        return $this->hasMany(Booking::class)->where('booking_status', 'confirmed');
    }

    /**
     * Scope for active packages
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive packages
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}