<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'customer';
    }

    public function rules(): array
    {
        return [
            'package_id'           => 'required|exists:packages,id',
            'caterer_id'           => 'required|exists:users,id',
            'event_type'           => 'required|string|max:255',
            'event_date'           => 'required|date|after:today',
            'time_slot'            => 'required|string|max:100',
            'guests'               => 'required|integer|min:1',
            'venue_name'           => 'required|string|max:255',
            'venue_address'        => 'required|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'selected_items'       => 'required|array|min:1',
            'selected_items.*'     => 'exists:menu_items,id',
            'price_per_head'       => 'required|numeric|min:0',
            'total_price'          => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'event_date.after' => 'Event date must be at least 1 day in advance.',
        ];
    }
}