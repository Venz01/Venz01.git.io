<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'is_default_template',
        'template_name',
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
        'is_default_template'   => 'boolean',
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

    public function costing()
    {
        return $this->hasOne(PackageCosting::class, 'package_id');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Get the default template for a given caterer.
     */
    public static function getDefaultForCaterer(int $userId): ?self
    {
        return self::where('user_id', $userId)
            ->where('is_default_template', true)
            ->first();
    }

    /**
     * All costings that are marked as templates for a given caterer.
     */
    public static function templatesForCaterer(int $userId)
    {
        return self::where('user_id', $userId)
            ->whereNotNull('package_id') // must be tied to a real package
            ->with('package:id,name')
            ->orderByDesc('is_default_template')
            ->orderBy('template_name')
            ->get();
    }

    /**
     * Set this costing as the default; clears the flag on all others for same user.
     */
    public function setAsDefault(): void
    {
        // Clear existing default for this caterer
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default_template' => false]);

        $this->update(['is_default_template' => true]);
    }

    /**
     * Apply this template's cost structure to a target PackageCosting instance
     * (does NOT save — caller must call save/updateOrCreate).
     */
    public function applyTo(self $target): self
    {
        $target->ingredient_cost       = $this->ingredient_cost;
        $target->labor_cost            = $this->labor_cost;
        $target->equipment_cost        = $this->equipment_cost;
        $target->consumables_cost      = $this->consumables_cost;
        $target->overhead_cost         = $this->overhead_cost;
        $target->transport_cost        = $this->transport_cost;
        $target->profit_margin_percent = $this->profit_margin_percent;
        // intentionally NOT copying final_price / suggested_price — those
        // are package-specific and will be recalculated

        return $target;
    }

    // ── Computed Attributes ───────────────────────────────────────────────────

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

    public function getProfitAmountAttribute(): float
    {
        return $this->total_cost * ($this->profit_margin_percent / 100);
    }

    public function getCalculatedPriceAttribute(): float
    {
        $raw = $this->total_cost + $this->profit_amount;
        return ceil($raw / 5) * 5;
    }

    public function getFilledComponentsCountAttribute(): int
    {
        $components = [
            'ingredient_cost', 'labor_cost', 'equipment_cost',
            'consumables_cost', 'overhead_cost', 'transport_cost',
        ];

        return collect($components)->filter(fn ($c) => !is_null($this->{$c}))->count();
    }

    public function getActualMarginPercentAttribute(): ?float
    {
        if (!$this->final_price || $this->total_cost == 0) {
            return null;
        }

        return (($this->final_price - $this->total_cost) / $this->final_price) * 100;
    }

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