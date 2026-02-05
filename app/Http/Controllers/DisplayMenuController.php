<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DisplayMenu;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Show the cart with menu items
     */
    public function cart()
    {
        $cart = session('menu_cart', []);
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $itemId => $quantity) {
            $menuItem = DisplayMenu::find($itemId);
            if ($menuItem) {
                $itemSubtotal = $menuItem->price * $quantity;
                $cartItems[] = [
                    'menu_item' => $menuItem,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal
                ];
                $subtotal += $itemSubtotal;
            }
        }

        return view('customer.orders.cart', compact('cartItems', 'subtotal'));
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request, $menuItemId)
    {
        $menuItem = DisplayMenu::findOrFail($menuItemId);

        // Check if item is active
        if ($menuItem->status !== 'active') {
            return back()->with('error', 'This item is currently unavailable.');
        }

        $cart = session('menu_cart', []);
        
        if (isset($cart[$menuItemId])) {
            $cart[$menuItemId] += $request->input('quantity', 1);
        } else {
            $cart[$menuItemId] = $request->input('quantity', 1);
        }

        session(['menu_cart' => $cart]);

        return back()->with('success', 'Item added to cart!');
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request, $menuItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        $cart = session('menu_cart', []);
        
        if (isset($cart[$menuItemId])) {
            $cart[$menuItemId] = $request->quantity;
            session(['menu_cart' => $cart]);
            return back()->with('success', 'Cart updated!');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($menuItemId)
    {
        $cart = session('menu_cart', []);
        
        if (isset($cart[$menuItemId])) {
            unset($cart[$menuItemId]);
            session(['menu_cart' => $cart]);
            return back()->with('success', 'Item removed from cart.');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        session()->forget('menu_cart');
        return back()->with('success', 'Cart cleared.');
    }

    /**
     * Show checkout page
     */
    public function checkout()
    {
        $cart = session('menu_cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.orders.cart')->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $subtotal = 0;
        $catererId = null;

        foreach ($cart as $itemId => $quantity) {
            $menuItem = DisplayMenu::with('caterer')->find($itemId);
            if ($menuItem) {
                // Check if all items are from the same caterer
                if ($catererId === null) {
                    $catererId = $menuItem->user_id;
                } elseif ($catererId !== $menuItem->user_id) {
                    return redirect()->route('customer.orders.cart')
                        ->with('error', 'All items must be from the same caterer. Please checkout separately.');
                }

                $itemSubtotal = $menuItem->price * $quantity;
                $cartItems[] = [
                    'menu_item' => $menuItem,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal
                ];
                $subtotal += $itemSubtotal;
            }
        }

        $caterer = \App\Models\User::find($catererId);
        $deliveryFee = 100; // Fixed delivery fee, can be made dynamic

        return view('customer.orders.checkout', compact('cartItems', 'subtotal', 'deliveryFee', 'caterer'));
    }

    /**
     * Process the order
     */
    public function processOrder(Request $request)
    {
        $request->validate([
            'fulfillment_type' => 'required|in:delivery,pickup',
            'fulfillment_date' => 'required|date|after:today',
            'fulfillment_time' => 'nullable',
            'delivery_address' => 'required_if:fulfillment_type,delivery|nullable|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:gcash,paymaya,bank_transfer,cod',
            'receipt' => 'required_unless:payment_method,cod|nullable|image|mimes:jpg,jpeg,png,gif,pdf|max:10240',
        ]);

        $cart = session('menu_cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.orders.cart')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Calculate totals and get caterer
            $subtotal = 0;
            $catererId = null;
            $orderItems = [];

            foreach ($cart as $itemId => $quantity) {
                $menuItem = DisplayMenu::find($itemId);
                if ($menuItem) {
                    if ($catererId === null) {
                        $catererId = $menuItem->user_id;
                    }

                    $itemSubtotal = $menuItem->price * $quantity;
                    $orderItems[] = [
                        'display_menu_id' => $menuItem->id,
                        'quantity' => $quantity,
                        'price' => $menuItem->price,
                        'subtotal' => $itemSubtotal
                    ];
                    $subtotal += $itemSubtotal;
                }
            }

            // Handle receipt upload
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $receiptPath = $request->file('receipt')->store('receipts/orders', 'public');
            }

            // Calculate fees
            $deliveryFee = $request->fulfillment_type === 'delivery' ? 100 : 0;
            $totalAmount = $subtotal + $deliveryFee;

            // Create order
            $order = Order::create([
                'customer_id' => auth()->id(),
                'caterer_id' => $catererId,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'fulfillment_type' => $request->fulfillment_type,
                'fulfillment_date' => $request->fulfillment_date,
                'fulfillment_time' => $request->fulfillment_time,
                'delivery_address' => $request->delivery_address,
                'special_instructions' => $request->special_instructions,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'receipt_path' => $receiptPath,
                'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'paid',
                'order_status' => 'pending',
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'display_menu_id' => $item['display_menu_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // Send notifications
            try {
                Log::info('Creating notifications for order', ['order_id' => $order->id]);
                
                // You can create similar notification methods for orders
                // $this->notificationService->notifyOrderCreated($order);
                // $this->notificationService->notifyCatererNewOrder($order);
                
                Log::info('Notifications created successfully', ['order_id' => $order->id]);
            } catch (\Exception $e) {
                Log::error('Failed to create notifications', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Clear cart
            session()->forget('menu_cart');

            DB::commit();

            return redirect()->route('customer.orders.confirmation', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to create order. Please try again.');
        }
    }

    /**
     * Show order confirmation
     */
    public function confirmation($orderId)
    {
        $order = Order::with(['caterer', 'items.displayMenu'])
            ->where('customer_id', auth()->id())
            ->findOrFail($orderId);

        return view('customer.orders.confirmation', compact('order'));
    }

    /**
     * Show customer's orders
     */
    public function index(Request $request)
    {
        $query = Order::where('customer_id', auth()->id())
            ->with(['caterer', 'items.displayMenu'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(10);

        // Get statistics
        $stats = [
            'pending' => Order::where('customer_id', auth()->id())->where('order_status', 'pending')->count(),
            'confirmed' => Order::where('customer_id', auth()->id())->where('order_status', 'confirmed')->count(),
            'completed' => Order::where('customer_id', auth()->id())->where('order_status', 'completed')->count(),
            'cancelled' => Order::where('customer_id', auth()->id())->where('order_status', 'cancelled')->count(),
        ];

        return view('customer.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show order details
     */
    public function show($orderId)
    {
        $order = Order::with(['caterer', 'items.displayMenu'])
            ->where('customer_id', auth()->id())
            ->findOrFail($orderId);

        return view('customer.orders.details', compact('order'));
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, $orderId)
    {
        $order = Order::where('customer_id', auth()->id())
            ->where('id', $orderId)
            ->firstOrFail();

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update([
            'order_status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason ?? 'No reason provided'
        ]);

        return redirect()->route('customer.orders.index')
            ->with('success', 'Order has been cancelled successfully.');
    }
}