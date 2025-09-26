<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model 
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'name', 
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the items for this category
     */
    public function items() 
    {
        return $this->hasMany(MenuItem::class);
    }

    /**
     * Get the user who owns this category
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get only available items for this category
     */
    public function availableItems()
    {
        return $this->hasMany(MenuItem::class)->where('status', 'available');
    }

    /**
     * Scope to get categories for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if category can be deleted (has no items)
     */
    public function canBeDeleted()
    {
        return $this->items()->count() === 0;
    }
}