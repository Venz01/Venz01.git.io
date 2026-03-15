<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Package extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'price', 'pax', 'status', 'image_path', 'dietary_tags'
    ];

    protected $casts = [
        'dietary_tags' => 'array',
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

    /**
     * Check if package has a specific dietary tag
     */
    public function hasDietaryTag(string $tag): bool
    {
        return in_array($tag, (array) ($this->dietary_tags ?? []), true);
    }

    /**
     * Get dietary tags as human-readable labels.
     * Uses DietaryTag model so labels stay in sync with the admin-managed tag list.
     */
    public function getDietaryLabelsAttribute(): array
    {
        $labels = \App\Models\DietaryTag::pluck('name', 'slug')->toArray();

        return array_values(array_map(
            fn ($key) => $labels[$key] ?? ucfirst(str_replace('_', ' ', $key)),
            (array) ($this->dietary_tags ?? [])
        ));
    }

    public function costing(): HasOne
    {
        return $this->hasOne(\App\Models\PackageCosting::class);
    }
}