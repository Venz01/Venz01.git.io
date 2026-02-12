<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PackageCosting extends Model
{
    protected $fillable = [
        'package_id',
        'user_id',
        'ingredient_cost',
        'labor_cost',
        'equipment_cost',
        'consumables_cost',
        'overhead_cost',
        'transport_cost',
        'profit_margin_percent',
        'suggested_price',
        'final_price',
        'notes',
    ];

    protected $casts = [
        'ingredient_cost'       => 'decimal:2',
        'labor_cost'            => 'decimal:2',
        'equipment_cost'        => 'decimal:2',
        'consumables_cost'      => 'decimal:2',
        'overhead_cost'         => 'decimal:2',
        'transport_cost'        => 'decimal:2',
        'profit_margin_percent' => 'decimal:2',
        'suggested_price'       => 'decimal:2',
        'final_price'           => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function caterer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Computed Helpers ──────────────────────────────────────────────────────

    /**
     * Sum of all active cost components (per head).
     */
    public function getTotalCostAttribute(): float
    {
        return (float) (
            ($this->ingredient_cost  ?? 0) +
            ($this->labor_cost       ?? 0) +
            ($this->equipment_cost   ?? 0) +
            ($this->consumables_cost ?? 0) +
            ($this->overhead_cost    ?? 0) +
            ($this->transport_cost   ?? 0)
        );
    }

    /**
     * Profit amount per head based on total_cost × margin %.
     */
    public function getProfitAmountAttribute(): float
    {
        return $this->total_cost * ($this->profit_margin_percent / 100);
    }

    /**
     * Calculated suggested price per head (total + profit), rounded to nearest 5.
     */
    public function getCalculatedPriceAttribute(): float
    {
        $raw = $this->total_cost + $this->profit_amount;
        return ceil($raw / 5) * 5; // round up to nearest ₱5
    }

    /**
     * How many of the 6 cost components the caterer has filled in.
     */
    public function getFilledComponentsCountAttribute(): int
    {
        $components = [
            'ingredient_cost', 'labor_cost', 'equipment_cost',
            'consumables_cost', 'overhead_cost', 'transport_cost',
        ];

        return collect($components)->filter(fn ($c) => !is_null($this->{$c}))->count();
    }

    /**
     * Percentage margin between final price and total cost.
     * Returns null if final_price not yet set.
     */
    public function getActualMarginPercentAttribute(): ?float
    {
        if (!$this->final_price || $this->total_cost == 0) {
            return null;
        }

        return (($this->final_price - $this->total_cost) / $this->final_price) * 100;
    }

    /**
     * Returns a human-readable array of each cost component for display.
     */
    public function getCostBreakdownAttribute(): array
    {
        $items = [
            ['label' => 'Ingredients / Raw Food',  'key' => 'ingredient_cost'],
            ['label' => 'Labor & Staffing',         'key' => 'labor_cost'],
            ['label' => 'Equipment & Rentals',      'key' => 'equipment_cost'],
            ['label' => 'Consumables & Packaging',  'key' => 'consumables_cost'],
            ['label' => 'Overhead & Utilities',     'key' => 'overhead_cost'],
            ['label' => 'Transport & Logistics',    'key' => 'transport_cost'],
        ];

        return collect($items)
            ->filter(fn ($item) => !is_null($this->{$item['key']}))
            ->map(fn ($item) => [
                'label'   => $item['label'],
                'amount'  => (float) $this->{$item['key']},
                'percent' => $this->total_cost > 0
                    ? round(($this->{$item['key']} / $this->total_cost) * 100, 1)
                    : 0,
            ])
            ->values()
            ->toArray();
    }
}