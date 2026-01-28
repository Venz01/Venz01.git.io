<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('customer.caterer.profile', $package->user->id) }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $package->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Package Hero -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                        <div class="relative h-96 bg-gradient-to-r from-gray-300 to-gray-400">
                            @if($package->image_path)
                                <img src="{{ $package->image_path }}" 
                                     alt="{{ $package->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center">
                                    <svg class="w-32 h-32 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-6 left-6">
                                <span class="inline-block px-4 py-2 text-sm font-medium bg-white bg-opacity-90 text-gray-800 rounded-full">
                                    @if(str_contains(strtolower($package->name), 'wedding'))
                                        Wedding Package
                                    @elseif(str_contains(strtolower($package->name), 'corporate'))
                                        Corporate Package
                                    @elseif(str_contains(strtolower($package->name), 'party'))
                                        Party Package
                                    @elseif(str_contains(strtolower($package->name), 'birthday'))
                                        Birthday Package
                                    @else
                                        Event Package
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Package Description -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Package Description</h2>
                        <p class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed">
                            {{ $package->description }}
                        </p>
                    </div>

                    <!-- Customizable Menu Items -->
                    @if($package->items->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Customize Your Menu</h2>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        <span id="selectedCount">{{ $package->items->count() }}</span> items selected
                                    </span>
                                    <button 
                                        onclick="selectAll()" 
                                        class="text-green-600 hover:text-green-700 text-sm font-medium"
                                    >
                                        Select All
                                    </button>
                                    <button 
                                        onclick="deselectAll()" 
                                        class="text-red-600 hover:text-red-700 text-sm font-medium"
                                    >
                                        Deselect All
                                    </button>
                                </div>
                            </div>

                            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Check or uncheck items to customize your package. The price will update automatically based on your selections.
                                </p>
                            </div>
                            
                            @php
                                $itemsByCategory = $package->items->groupBy('category.name');
                            @endphp

                            <div class="space-y-8">
                                @foreach($itemsByCategory as $categoryName => $items)
                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                                {{ $categoryName ?? 'Uncategorized' }}
                                            </h3>
                                            <button 
                                                onclick="toggleCategory('{{ $categoryName }}')" 
                                                class="text-sm text-green-600 hover:text-green-700 font-medium"
                                            >
                                                Toggle All
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($items as $item)
                                                <div class="menu-item relative p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border-2 border-transparent transition-all hover:border-green-300"
                                                     data-category="{{ $categoryName }}"
                                                     data-item-id="{{ $item->id }}"
                                                     data-item-price="{{ $item->price }}">
                                                    <label class="flex items-start space-x-3 cursor-pointer">
                                                        <input 
                                                            type="checkbox" 
                                                            name="menu_items[]" 
                                                            value="{{ $item->id }}"
                                                            class="menu-item-checkbox w-5 h-5 text-green-600 rounded focus:ring-green-500 mt-1"
                                                            checked
                                                            onchange="updatePrice()"
                                                        >
                                                        <div class="flex-1">
                                                            <div class="flex items-start justify-between">
                                                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $item->name }}</h4>
                                                                @if($item->image_path)
                                                                    <img src="{{ $item->image_path }}"  
                                                                         alt="{{ $item->name }}"
                                                                         class="w-16 h-16 object-cover rounded-lg ml-2">
                                                                @endif
                                                            </div>
                                                            @if($item->description)
                                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $item->description }}</p>
                                                            @endif
                                                            <p class="text-sm font-medium text-green-600 mt-2">₱{{ number_format($item->price, 2) }}</p>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Price Breakdown -->
                            <div class="mt-8 p-6 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-xl">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Price Breakdown</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                        <span>Food Cost:</span>
                                        <span class="font-medium">₱<span id="foodCost">0</span></span>
                                    </div>
                                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                        <span>Labor & Utilities (20%):</span>
                                        <span class="font-medium">₱<span id="laborCost">0</span></span>
                                    </div>
                                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                        <span>Equipment & Transport (10%):</span>
                                        <span class="font-medium">₱<span id="equipmentCost">0</span></span>
                                    </div>
                                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                        <span>Profit Margin (25%):</span>
                                        <span class="font-medium">₱<span id="profitMargin">0</span></span>
                                    </div>
                                    <div class="border-t-2 border-gray-300 dark:border-gray-600 my-3"></div>
                                    <div class="flex justify-between text-base font-semibold text-gray-900 dark:text-white">
                                        <span>Price per Head:</span>
                                        <span>₱<span id="pricePerHead">{{ number_format($package->price, 0) }}</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar - Sticky Booking Card -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-6">
                        <!-- Prominent Total Price Display -->
                        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl p-6 text-white mb-6">
                            <div class="text-center">
                                <p class="text-sm font-medium opacity-90 mb-2">Total Package Price</p>
                                <div class="text-5xl font-bold mb-2">
                                    ₱<span id="totalPriceMain">{{ number_format($package->price * $package->pax, 0) }}</span>
                                </div>
                                <div class="text-sm opacity-90">
                                    <span id="perHeadPriceDisplay">₱{{ number_format($package->price, 0) }}</span> per head × 
                                    <span id="guestCountDisplay">{{ $package->pax }}</span> guests
                                </div>
                            </div>
                        </div>

                        <form id="bookingForm" action="{{ route('customer.booking.store-event') }}" method="POST" class="space-y-4 mb-6">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <input type="hidden" name="caterer_id" value="{{ $package->user_id }}">
                            <input type="hidden" name="price_per_head" id="hiddenPricePerHead" value="{{ $package->price }}">
                            <input type="hidden" name="total_price" id="hiddenTotalPrice" value="{{ $package->price * $package->pax }}">
                            <input type="hidden" name="selected_items_json" id="selectedItemsJson">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Number of Guests *</label>
                                <input 
                                    type="number" 
                                    name="guests"
                                    id="guestCount"
                                    placeholder="{{ $package->pax }}" 
                                    value="{{ $package->pax }}"
                                    min="1"
                                    max="1000"
                                    class="w-full px-4 py-3 text-lg font-semibold border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    onchange="updateTotalPrice()"
                                    oninput="updateTotalPrice()"
                                    required
                                >
                                <p class="text-xs text-gray-500 mt-1">Minimum package serves {{ $package->pax }} guests</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event Type *</label>
                                <select 
                                    name="event_type"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required
                                >
                                    <option value="">Select event type</option>
                                    <option value="Wedding">Wedding</option>
                                    <option value="Corporate Event">Corporate Event</option>
                                    <option value="Birthday Party">Birthday Party</option>
                                    <option value="Anniversary">Anniversary</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event Date *</label>
                                <input 
                                    type="date" 
                                    name="event_date"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Time Slot *</label>
                                <select 
                                    name="time_slot"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required
                                >
                                    <option value="">Select time slot</option>
                                    <option value="Morning (6:00 AM - 12:00 PM)">Morning (6:00 AM - 12:00 PM)</option>
                                    <option value="Afternoon (12:00 PM - 6:00 PM)">Afternoon (12:00 PM - 6:00 PM)</option>
                                    <option value="Evening (6:00 PM - 12:00 AM)">Evening (6:00 PM - 12:00 AM)</option>
                                    <option value="Whole Day (6:00 AM - 12:00 AM)">Whole Day (6:00 AM - 12:00 AM)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Venue Name *</label>
                                <input 
                                    type="text" 
                                    name="venue_name"
                                    placeholder="e.g., Grand Ballroom, Home Address, etc."
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Venue Address *</label>
                                <textarea 
                                    name="venue_address"
                                    rows="2"
                                    placeholder="Full address of the venue"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Special Instructions (Optional)</label>
                                <textarea 
                                    name="special_instructions"
                                    rows="3"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Any dietary restrictions or special requirements..."
                                ></textarea>
                            </div>
                        </form>

                        <div class="space-y-3">
                            <button 
                                onclick="submitBooking()"
                                class="w-full bg-green-600 text-white py-4 px-6 rounded-xl font-semibold hover:bg-green-700 transition-colors flex items-center justify-center text-lg"
                            >
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Book Now
                            </button>
                            <a 
                                href="{{ route('customer.caterer.profile', $package->user->id) }}"
                                class="block w-full text-center border-2 border-gray-300 text-gray-700 py-3 px-6 rounded-xl font-semibold hover:bg-gray-50 transition-colors"
                            >
                                Cancel
                            </a>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                <div class="flex justify-between">
                                    <span>Deposit Required (25%):</span>
                                    <span class="font-medium text-green-600">₱<span id="depositAmount">{{ number_format(($package->price * $package->pax) * 0.25, 0) }}</span></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Service Fee:</span>
                                    <span class="font-medium">₱500</span>
                                </div>
                                <div class="flex justify-between font-semibold text-base text-gray-900 dark:text-white">
                                    <span>Due Now:</span>
                                    <span class="text-green-600">₱<span id="dueNow">{{ number_format((($package->price * $package->pax) * 0.25) + 500, 0) }}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Caterer Info Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">About the Caterer</h3>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white text-xl font-bold">
                                {{ substr($package->user->business_name ?? $package->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">
                                    {{ $package->user->business_name ?? $package->user->name }}
                                </h4>
                                <div class="flex items-center mt-1">
                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="ml-1 text-sm text-gray-600 dark:text-gray-400">4.8 • 156 reviews</span>
                                </div>
                            </div>
                        </div>
                        
                        @if($package->user->business_address)
                            <div class="flex items-center text-gray-600 dark:text-gray-400 mb-4">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $package->user->business_address }}
                            </div>
                        @endif

                        <div class="space-y-3">
                            <a 
                                href="{{ route('customer.caterer.profile', $package->user->id) }}" 
                                class="block w-full text-center border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                            >
                                View Profile
                            </a>
                            @if($package->user->contact_number)
                                <a 
                                    href="tel:{{ $package->user->contact_number }}"
                                    class="block w-full text-center bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors font-medium"
                                >
                                    Contact Caterer
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for customization and price calculation -->
    <script>
        // Store original package data
        const originalPrice = {{ $package->price }};
        const originalPax = {{ $package->pax }};
        
        // Calculate price based on selected items
        function updatePrice() {
            const checkboxes = document.querySelectorAll('.menu-item-checkbox:checked');
            let foodCost = 0;
            
            const selectedItems = [];
            checkboxes.forEach(checkbox => {
                const price = parseFloat(checkbox.closest('.menu-item').dataset.itemPrice);
                foodCost += price;
                selectedItems.push(checkbox.value);
            });
            
            // Calculate markups
            const laborAndUtilities = foodCost * 0.20;
            const equipmentTransport = foodCost * 0.10;
            const profitMargin = foodCost * 0.25;
            
            // Calculate total price per head
            let pricePerHead = foodCost + laborAndUtilities + equipmentTransport + profitMargin;
            pricePerHead = Math.round(pricePerHead / 5) * 5; // Round to nearest 5
            
            // Update breakdown displays
            document.getElementById('foodCost').textContent = foodCost.toFixed(2);
            document.getElementById('laborCost').textContent = laborAndUtilities.toFixed(2);
            document.getElementById('equipmentCost').textContent = equipmentTransport.toFixed(2);
            document.getElementById('profitMargin').textContent = profitMargin.toFixed(2);
            document.getElementById('pricePerHead').textContent = pricePerHead.toLocaleString();
            document.getElementById('perHeadPriceDisplay').textContent = '₱' + pricePerHead.toLocaleString();
            
            // Update hidden form fields
            document.getElementById('hiddenPricePerHead').value = pricePerHead;
            document.getElementById('selectedItemsJson').value = JSON.stringify(selectedItems);
            
            // Update selected count
            document.getElementById('selectedCount').textContent = checkboxes.length;
            
            // Update total price
            updateTotalPrice();
            
            // Visual feedback for selected items
            document.querySelectorAll('.menu-item').forEach(item => {
                const checkbox = item.querySelector('.menu-item-checkbox');
                if (checkbox.checked) {
                    item.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900');
                } else {
                    item.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900');
                }
            });
        }
        
        // Update total price based on guest count
        function updateTotalPrice() {
            const guests = parseInt(document.getElementById('guestCount').value) || originalPax;
            const pricePerHead = parseFloat(document.getElementById('hiddenPricePerHead').value || originalPrice);
            const totalPrice = pricePerHead * guests;
            
            // Update all total price displays
            document.getElementById('totalPriceMain').textContent = totalPrice.toLocaleString();
            document.getElementById('guestCountDisplay').textContent = guests;
            document.getElementById('hiddenTotalPrice').value = totalPrice;
            
            // Update deposit and due now
            const deposit = totalPrice * 0.25;
            const dueNow = deposit + 500;
            document.getElementById('depositAmount').textContent = deposit.toLocaleString();
            document.getElementById('dueNow').textContent = dueNow.toLocaleString();
        }
        
        // Select all items
        function selectAll() {
            document.querySelectorAll('.menu-item-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            updatePrice();
        }
        
        // Deselect all items
        function deselectAll() {
            document.querySelectorAll('.menu-item-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            updatePrice();
        }
        
        // Toggle category items
        function toggleCategory(categoryName) {
            const categoryItems = document.querySelectorAll(`.menu-item[data-category="${categoryName}"] .menu-item-checkbox`);
            const allChecked = Array.from(categoryItems).every(cb => cb.checked);
            
            categoryItems.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
            updatePrice();
        }
        
        // Submit booking
        function submitBooking() {
            const form = document.getElementById('bookingForm');
            
            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Get selected items
            const selectedItems = Array.from(document.querySelectorAll('.menu-item-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selectedItems.length === 0) {
                alert('Please select at least one menu item.');
                return;
            }
            
            // Update hidden field with selected items
            document.getElementById('selectedItemsJson').value = JSON.stringify(selectedItems);
            
            // Add selected items as hidden inputs
            const form_element = document.getElementById('bookingForm');
            selectedItems.forEach(itemId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_items[]';
                input.value = itemId;
                form_element.appendChild(input);
            });
            
            // Submit the form
            form.submit();
        }
        
        // Initialize price calculation on page load
        document.addEventListener('DOMContentLoaded', function() {
            updatePrice();
            
            // Add input event listener for real-time guest count updates
            document.getElementById('guestCount').addEventListener('input', function() {
                updateTotalPrice();
            });
        });
    </script>

    <style>
        .menu-item {
            transition: all 0.3s ease;
        }
        
        .menu-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .menu-item-checkbox:checked + div {
            opacity: 1;
        }
        
        .menu-item-checkbox:not(:checked) + div {
            opacity: 0.7;
        }

        /* Smooth number transitions */
        #totalPriceMain, #perHeadPriceDisplay, #guestCountDisplay,
        #depositAmount, #dueNow {
            transition: all 0.3s ease;
        }
    </style>
</x-app-layout>