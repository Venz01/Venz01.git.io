<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCostingRequest extends FormRequest
{
    /**
     * Only the authenticated caterer who owns the package may submit this.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Validation rules for saving a package costing.
     */
    public function rules(): array
    {
        return [
            // Cost breakdown fields — all optional (default to 0 in the controller)
            'ingredient_cost'       => ['nullable', 'numeric', 'min:0'],
            'labor_cost'            => ['nullable', 'numeric', 'min:0'],
            'equipment_cost'        => ['nullable', 'numeric', 'min:0'],
            'consumables_cost'      => ['nullable', 'numeric', 'min:0'],
            'overhead_cost'         => ['nullable', 'numeric', 'min:0'],
            'transport_cost'        => ['nullable', 'numeric', 'min:0'],

            // Profit margin — between 0% and 100%
            'profit_margin_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],

            // Final price the caterer wants to charge (optional override)
            'final_price'           => ['nullable', 'numeric', 'min:0'],

            // Free-text notes about the costing
            'notes'                 => ['nullable', 'string', 'max:2000'],

            // Boolean flags (checkboxes)
            'set_as_default'        => ['nullable', 'boolean'],
            'apply_to_package'      => ['nullable', 'boolean'],
        ];
    }

    /**
     * Human-readable error messages.
     */
    public function messages(): array
    {
        return [
            'ingredient_cost.numeric'       => 'Ingredient cost must be a valid number.',
            'ingredient_cost.min'           => 'Ingredient cost cannot be negative.',
            'labor_cost.numeric'            => 'Labor cost must be a valid number.',
            'labor_cost.min'                => 'Labor cost cannot be negative.',
            'equipment_cost.numeric'        => 'Equipment cost must be a valid number.',
            'equipment_cost.min'            => 'Equipment cost cannot be negative.',
            'consumables_cost.numeric'      => 'Consumables cost must be a valid number.',
            'consumables_cost.min'          => 'Consumables cost cannot be negative.',
            'overhead_cost.numeric'         => 'Overhead cost must be a valid number.',
            'overhead_cost.min'             => 'Overhead cost cannot be negative.',
            'transport_cost.numeric'        => 'Transport cost must be a valid number.',
            'transport_cost.min'            => 'Transport cost cannot be negative.',
            'profit_margin_percent.numeric' => 'Profit margin must be a valid number.',
            'profit_margin_percent.min'     => 'Profit margin cannot be negative.',
            'profit_margin_percent.max'     => 'Profit margin cannot exceed 100%.',
            'final_price.numeric'           => 'Final price must be a valid number.',
            'final_price.min'               => 'Final price cannot be negative.',
            'notes.max'                     => 'Notes cannot exceed 2,000 characters.',
        ];
    }
}