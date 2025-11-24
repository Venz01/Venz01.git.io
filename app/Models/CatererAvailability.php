<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatererAvailability extends Model
{
    use HasFactory;

    protected $table = 'caterer_availability';

    protected $fillable = [
        'caterer_id',
        'date',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the caterer that owns the availability
     */
    public function caterer()
    {
        return $this->belongsTo(User::class, 'caterer_id');
    }

    /**
     * Scope for available dates
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope for blocked dates
     */
    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    /**
     * Scope for booked dates
     */
    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    /**
     * Scope for specific caterer
     */
    public function scopeForCaterer($query, $catererId)
    {
        return $query->where('caterer_id', $catererId);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}