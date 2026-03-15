<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessBalancePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'customer';
    }

    public function rules(): array
    {
        return [
            'receipt'        => 'required|mimes:jpg,jpeg,png,gif,pdf|max:10240',
            'payment_method' => 'required|in:gcash,paymaya,bank_transfer',
        ];
    }
}