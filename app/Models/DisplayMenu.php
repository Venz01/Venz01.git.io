<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisplayMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'description',
        'price',
        'unit_type', // NEW: 'tray', 'bilao', 'piece', 'platter', etc.
        'image_path',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the caterer who owns this display menu
     */
    public function caterer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get orders that include this menu item
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'price', 'subtotal')
            ->withTimestamps();
    }

    /**
     * Scope for active menus
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive menus
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope to get menus by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get formatted unit type
     */
    public function getFormattedUnitAttribute()
    {
        return ucfirst($this->unit_type ?? 'item');
    }
}