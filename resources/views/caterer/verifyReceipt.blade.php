<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Verify Payment Receipts') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @php
                $bookingsWithReceipts = \App\Models\Booking::where('caterer_id', auth()->id())
                    ->whereNotNull('receipt_path')
                    ->with('customer')
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                $pendingVerification = $bookingsWithReceipts->where('booking_status', 'pending')->count();
            @endphp

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md rounded-xl transition-shadow">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg p-3">
                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pending Verification</dt>
                                <dd class="mt-1">
                                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $pendingVerification }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md rounded-xl transition-shadow">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/30 rounded-lg p-3">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Receipts</dt>
                                <dd class="mt-1">
                                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $bookingsWithReceipts->count() }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md rounded-xl transition-shadow">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/30 rounded-lg p-3">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Verified</dt>
                                <dd class="mt-1">
                                    <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $bookingsWithReceipts->whereIn('booking_status', ['confirmed', 'completed'])->count() }}
                                    </div>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Receipts List --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Payment Receipts</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Review and verify payment receipts from customers</p>
                </div>
                
                @if($bookingsWithReceipts->count() > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($bookingsWithReceipts as $booking)
                    <div class="p-4 sm:p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="flex items-start gap-4 flex-1">
                                <div class="flex-shrink-0">
                                    @if($booking->booking_status === 'pending')
                                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100 truncate">
                                        Booking #{{ $booking->booking_number }}
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                        {{ $booking->customer_name }}
                                    </p>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                        {{ $booking->customer_email }}
                                    </p>
                                    
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                            {{ $booking->payment_method === 'gcash' ? 'GCash' : ($booking->payment_method === 'paymaya' ? 'PayMaya' : 'Bank Transfer') }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            ₱{{ number_format($booking->payment_status === 'deposit_paid' ? $booking->deposit_paid : $booking->total_price, 2) }}
                                        </span>
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold
                                            @if($booking->payment_status === 'fully_paid') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row gap-2 sm:items-center sm:ml-4">
                                <a href="{{ Storage::url($booking->receipt_path) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span class="hidden sm:inline">View Receipt</span>
                                    <span class="sm:hidden">Receipt</span>
                                </a>
                                <a href="{{ route('caterer.booking.details', $booking->id) }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                                    <span class="hidden sm:inline">View Booking</span>
                                    <span class="sm:hidden">Booking</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-8 sm:p-12 text-center">
                    <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-4 text-base sm:text-lg font-medium text-gray-900 dark:text-gray-100">No Receipts Yet</h3>
                    <p class="mt-2 text-sm sm:text-base text-gray-500 dark:text-gray-400">You haven't received any payment receipts yet.</p>
                </div>
                @endif
            </div>

            {{-- Instructions Card --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-3">Verification Instructions</h3>
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 mt-0.5 mr-2 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Review the receipt image to verify the payment details</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 mt-0.5 mr-2 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Check that the amount matches the deposit or full payment</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 mt-0.5 mr-2 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Confirm the payment method (GCash/PayMaya/Bank Transfer)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 mt-0.5 mr-2 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Once verified, confirm the booking to proceed</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 mt-0.5 mr-2 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>For suspicious payments, you can reject the booking with a reason</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>