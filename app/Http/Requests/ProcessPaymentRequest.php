<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'customer';
    }

    public function rules(): array
    {
        return [
            'full_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'payment_method' => 'required|in:gcash,paymaya,bank_transfer',
            'receipt'        => 'required|mimes:jpg,jpeg,png,gif,pdf|max:10240',
        ];
    }
}