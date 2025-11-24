<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Calendar & Availability') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_bookings'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Confirmed</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['confirmed'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Blocked Days</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['blocked_days'] }}</p>
                </div>
            </div>

            <!-- Calendar Controls -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <!-- Month Navigation -->
                        <div class="flex items-center gap-4">
                            <a href="{{ route('caterer.calendar', ['year' => $startDate->copy()->subMonth()->year, 'month' => $startDate->copy()->subMonth()->month]) }}" 
                               class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                                ← Previous
                            </a>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $startDate->format('F Y') }}
                            </h3>
                            <a href="{{ route('caterer.calendar', ['year' => $startDate->copy()->addMonth()->year, 'month' => $startDate->copy()->addMonth()->month]) }}" 
                               class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                                Next →
                            </a>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <button onclick="document.getElementById('blockSingleModal').classList.remove('hidden')" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Block Single Date
                            </button>
                            <button onclick="document.getElementById('blockRangeModal').classList.remove('hidden')" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Block Date Range
                            </button>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="flex flex-wrap gap-4 mb-6 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-blue-200 border border-blue-400 rounded"></div>
                            <span class="text-gray-700 dark:text-gray-300">Confirmed Booking</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-yellow-200 border border-yellow-400 rounded"></div>
                            <span class="text-gray-700 dark:text-gray-300">Pending Booking</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-red-200 border border-red-400 rounded"></div>
                            <span class="text-gray-700 dark:text-gray-300">Blocked Date</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-gray-100 border border-gray-300 rounded"></div>
                            <span class="text-gray-700 dark:text-gray-300">Available</span>
                        </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-7 gap-2">
                        <!-- Day Headers -->
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="text-center font-semibold text-gray-700 dark:text-gray-300 py-2">
                            {{ $day }}
                        </div>
                        @endforeach

                        <!-- Calendar Days -->
                        @php
                            $currentDate = $startDate->copy()->startOfWeek();
                            $endOfCalendar = $endDate->copy()->endOfWeek();
                        @endphp

                        @while($currentDate <= $endOfCalendar)
                            @php
                                $dateKey = $currentDate->format('Y-m-d');
                                $isCurrentMonth = $currentDate->month === $startDate->month;
                                $isPast = $currentDate->isPast() && !$currentDate->isToday();
                                
                                $dayBookings = $bookings->get($dateKey, collect());
                                $dayAvailability = $availability->get($dateKey);
                                
                                $bgClass = 'bg-white dark:bg-gray-700';
                                $borderClass = 'border-gray-300';
                                $textClass = 'text-gray-900 dark:text-gray-100';
                                
                                if (!$isCurrentMonth) {
                                    $bgClass = 'bg-gray-50 dark:bg-gray-800';
                                    $textClass = 'text-gray-400';
                                } elseif ($isPast) {
                                    $bgClass = 'bg-gray-100 dark:bg-gray-800';
                                } elseif ($dayAvailability && $dayAvailability->status === 'blocked') {
                                    $bgClass = 'bg-red-100 dark:bg-red-900';
                                    $borderClass = 'border-red-400';
                                } elseif ($dayBookings->where('booking_status', 'confirmed')->count() > 0) {
                                    $bgClass = 'bg-blue-100 dark:bg-blue-900';
                                    $borderClass = 'border-blue-400';
                                } elseif ($dayBookings->where('booking_status', 'pending')->count() > 0) {
                                    $bgClass = 'bg-yellow-100 dark:bg-yellow-900';
                                    $borderClass = 'border-yellow-400';
                                }
                            @endphp
                            
                            <div class="min-h-24 border {{ $borderClass }} {{ $bgClass }} rounded-lg p-2 {{ $isCurrentMonth ? 'hover:shadow-md transition-shadow cursor-pointer' : '' }}"
                                 onclick="showDateDetails('{{ $dateKey }}', {{ $dayBookings->toJson() }}, {{ $dayAvailability ? $dayAvailability->toJson() : 'null' }})">
                                <div class="font-semibold {{ $textClass }} {{ $currentDate->isToday() ? 'bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center' : '' }}">
                                    {{ $currentDate->day }}
                                </div>
                                
                                @if($isCurrentMonth && !$isPast)
                                    @foreach($dayBookings as $booking)
                                        <div class="text-xs mt-1 p-1 rounded truncate
                                            @if($booking->booking_status === 'confirmed') bg-blue-200 dark:bg-blue-800 text-blue-900 dark:text-blue-100
                                            @elseif($booking->booking_status === 'pending') bg-yellow-200 dark:bg-yellow-800 text-yellow-900 dark:text-yellow-100
                                            @endif">
                                            {{ $booking->customer_name }}
                                        </div>
                                    @endforeach
                                    
                                    @if($dayAvailability && $dayAvailability->status === 'blocked')
                                        <div class="text-xs mt-1 p-1 bg-red-200 dark:bg-red-800 text-red-900 dark:text-red-100 rounded">
                                            Blocked
                                        </div>
                                    @endif
                                @endif
                            </div>
                            
                            @php $currentDate->addDay(); @endphp
                        @endwhile
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Block Single Date Modal -->
    <div id="blockSingleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Block Single Date</h3>
                <form action="{{ route('caterer.availability.toggle') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                        <input type="date" name="date" min="{{ now()->format('Y-m-d') }}" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="blocked">Block Date</option>
                            <option value="available">Unblock Date</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (Optional)</label>
                        <textarea name="notes" rows="3" 
                                  class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Save
                        </button>
                        <button type="button" onclick="document.getElementById('blockSingleModal').classList.add('hidden')"
                                class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Block Date Range Modal -->
    <div id="blockRangeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Block Date Range</h3>
                <form action="{{ route('caterer.availability.block-range') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                        <input type="date" name="start_date" min="{{ now()->format('Y-m-d') }}" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                        <input type="date" name="end_date" min="{{ now()->format('Y-m-d') }}" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (Optional)</label>
                        <textarea name="notes" rows="3" 
                                  class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                  placeholder="e.g., Vacation, Holiday"></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Block Range
                        </button>
                        <button type="button" onclick="document.getElementById('blockRangeModal').classList.add('hidden')"
                                class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Date Details Modal -->
    <div id="dateDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 id="dateDetailsTitle" class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4"></h3>
                <div id="dateDetailsContent"></div>
                <button type="button" onclick="document.getElementById('dateDetailsModal').classList.add('hidden')"
                        class="mt-4 w-full px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function showDateDetails(date, bookings, availability) {
            const modal = document.getElementById('dateDetailsModal');
            const title = document.getElementById('dateDetailsTitle');
            const content = document.getElementById('dateDetailsContent');
            
            const dateObj = new Date(date);
            title.textContent = dateObj.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            
            let html = '';
            
            if (bookings && bookings.length > 0) {
                html += '<div class="mb-4"><h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Bookings:</h4>';
                bookings.forEach(booking => {
                    const statusClass = booking.booking_status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800';
                    html += `
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg mb-2">
                            <p class="font-medium text-gray-900 dark:text-gray-100">${booking.customer_name}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">${booking.event_type}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">${booking.guests} guests</p>
                            <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full ${statusClass}">
                                ${booking.booking_status.charAt(0).toUpperCase() + booking.booking_status.slice(1)}
                            </span>
                        </div>
                    `;
                });
                html += '</div>';
            }
            
            if (availability && availability.status === 'blocked') {
                html += `
                    <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <p class="font-semibold text-red-800 dark:text-red-200">Date is Blocked</p>
                        ${availability.notes ? `<p class="text-sm text-gray-600 dark:text-gray-400 mt-1">${availability.notes}</p>` : ''}
                    </div>
                `;
            } else if (!bookings || bookings.length === 0) {
                html += '<p class="text-gray-500 dark:text-gray-400 text-center py-4">No bookings for this date</p>';
            }
            
            content.innerHTML = html;
            modal.classList.remove('hidden');
        }
    </script>
</x-app-layout>