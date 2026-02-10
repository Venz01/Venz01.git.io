<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Calendar & Availability') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- ✅ UPDATED: Stats Cards with Orders --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Events</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ \App\Models\Booking::where('caterer_id', auth()->id())->count() + \App\Models\Order::where('caterer_id', auth()->id())->count() }}
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Bookings</p>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ \App\Models\Booking::where('caterer_id', auth()->id())->count() }}
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Orders</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ \App\Models\Order::where('caterer_id', auth()->id())->count() }}
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">This Month</p>
                    <p class="text-2xl font-bold text-purple-600">
                        {{ \App\Models\Booking::where('caterer_id', auth()->id())
                            ->whereMonth('event_date', now()->month)
                            ->whereYear('event_date', now()->year)
                            ->count() + 
                           \App\Models\Order::where('caterer_id', auth()->id())
                            ->whereMonth('fulfillment_date', now()->month)
                            ->whereYear('fulfillment_date', now()->year)
                            ->count() 
                        }}
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Blocked Days</p>
                    <p class="text-2xl font-bold text-red-600">
                        {{ \App\Models\CatererAvailability::where('caterer_id', auth()->id())->where('status', 'blocked')->count() }}
                    </p>
                </div>
            </div>

            <!-- Calendar -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <!-- Calendar Title -->
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Event Calendar
                        </h3>

                        <!-- Block Date Buttons -->
                        <div class="flex gap-2">
                            <button onclick="toggleBlockModal()" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                                Block Date
                            </button>
                        </div>
                    </div>

                    <!-- ✅ UPDATED: Legend with Order Types -->
                    <div class="flex flex-wrap gap-4 mb-6 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 rounded" style="background-color: #3B82F6;"></div>
                            <span class="text-gray-700 dark:text-gray-300">🎉 Event Booking</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 rounded" style="background-color: #10B981;"></div>
                            <span class="text-gray-700 dark:text-gray-300">📦 Menu Order</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-red-200 border border-red-400 rounded"></div>
                            <span class="text-gray-700 dark:text-gray-300">Blocked Date</span>
                        </div>
                    </div>

                    <!-- Calendar Container -->
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Block Date Modal -->
    <div id="blockDateModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Block Date</h3>
                <button onclick="toggleBlockModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('caterer.calendar.block-date') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Select Date
                    </label>
                    <input type="date" name="date" id="blockDate" required
                           min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea name="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100"
                              placeholder="Reason for blocking this date..."></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Block Date
                    </button>
                    <button type="button" onclick="toggleBlockModal()"
                            class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- FullCalendar CSS & JS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            // ✅ UPDATED: Events data with both bookings and orders
            var events = {!! json_encode($allEvents) !!};
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },
                events: events,
                eventClick: function(info) {
                    showEventDetails(info.event);
                },
                height: 'auto',
                eventDisplay: 'block',
                displayEventTime: false,
                eventDidMount: function(info) {
                    // Add tooltip
                    info.el.setAttribute('title', info.event.title);
                    
                    // Add emoji icon based on event type
                    if (info.event.extendedProps.type === 'booking') {
                        const icon = document.createElement('span');
                        icon.innerHTML = '🎉 ';
                        icon.style.marginRight = '4px';
                        info.el.querySelector('.fc-event-title').prepend(icon);
                    } else if (info.event.extendedProps.type === 'order') {
                        const icon = document.createElement('span');
                        icon.innerHTML = '📦 ';
                        icon.style.marginRight = '4px';
                        info.el.querySelector('.fc-event-title').prepend(icon);
                    }
                }
            });
            
            calendar.render();
        });

        function showEventDetails(event) {
            const props = event.extendedProps;
            
            let details = '';
            if (props.type === 'booking') {
                details = `
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-2xl">🎉</span>
                            <h4 class="text-lg font-semibold">Event Booking</h4>
                        </div>
                        <p><strong>Customer:</strong> ${props.customer_name}</p>
                        <p><strong>Event Type:</strong> ${props.event_type}</p>
                        <p><strong>Guests:</strong> ${props.guests}</p>
                        <p><strong>Venue:</strong> ${props.venue}</p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs ${getStatusClass(props.status)}">${props.status}</span></p>
                        <p><strong>Payment:</strong> <span class="px-2 py-1 rounded text-xs ${getPaymentStatusClass(props.payment_status)}">${props.payment_status}</span></p>
                        <div class="mt-4">
                            <a href="/caterer/bookings/${props.booking_id}" class="text-blue-600 hover:underline">View Full Details →</a>
                        </div>
                    </div>
                `;
            } else if (props.type === 'order') {
                details = `
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-2xl">📦</span>
                            <h4 class="text-lg font-semibold">Menu Order</h4>
                        </div>
                        <p><strong>Customer:</strong> ${props.customer_name}</p>
                        <p><strong>Type:</strong> ${props.fulfillment_type}</p>
                        ${props.fulfillment_time ? `<p><strong>Time:</strong> ${props.fulfillment_time}</p>` : ''}
                        <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs ${getOrderStatusClass(props.status)}">${props.status}</span></p>
                        <p><strong>Payment:</strong> <span class="px-2 py-1 rounded text-xs ${getPaymentStatusClass(props.payment_status)}">${props.payment_status}</span></p>
                        <div class="mt-4">
                            <a href="/caterer/orders/${props.order_id}" class="text-blue-600 hover:underline">View Full Details →</a>
                        </div>
                    </div>
                `;
            } else if (props.type === 'blocked') {
                details = `
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-2xl">🚫</span>
                            <h4 class="text-lg font-semibold">Blocked Date</h4>
                        </div>
                        <p class="text-red-600">This date is blocked and unavailable for bookings.</p>
                    </div>
                `;
            }
            
            Swal.fire({
                html: details,
                showCloseButton: true,
                showConfirmButton: false,
                width: '500px'
            });
        }

        function getStatusClass(status) {
            const classes = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'confirmed': 'bg-blue-100 text-blue-800',
                'completed': 'bg-green-100 text-green-800',
                'cancelled': 'bg-red-100 text-red-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }

        function getOrderStatusClass(status) {
            const classes = {
                'pending': 'bg-amber-100 text-amber-800',
                'confirmed': 'bg-indigo-100 text-indigo-800',
                'preparing': 'bg-purple-100 text-purple-800',
                'ready': 'bg-teal-100 text-teal-800',
                'completed': 'bg-emerald-100 text-emerald-800',
                'cancelled': 'bg-red-100 text-red-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }

        function getPaymentStatusClass(status) {
            const classes = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'deposit_paid': 'bg-yellow-100 text-yellow-800',
                'paid': 'bg-green-100 text-green-800',
                'fully_paid': 'bg-green-100 text-green-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }

        function toggleBlockModal() {
            const modal = document.getElementById('blockDateModal');
            modal.classList.toggle('hidden');
        }
    </script>

    <!-- SweetAlert for event details popup -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</x-app-layout>