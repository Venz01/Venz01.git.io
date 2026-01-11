<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CatererController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Public route
Route::get('/', function () {
    return view('welcome');
});

// Redirect /dashboard to role-specific dashboard
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'caterer') {
        return redirect()->route('caterer.dashboard');
    } else {
        return redirect()->route('customer.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');


// Profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Portfolio management (Caterers only)
    Route::post('/profile/portfolio/upload', [ProfileController::class, 'uploadPortfolio'])->name('profile.portfolio.upload');
    Route::delete('/profile/portfolio/{id}', [ProfileController::class, 'deletePortfolio'])->name('profile.portfolio.delete');
    Route::patch('/profile/portfolio/{id}/toggle-featured', [ProfileController::class, 'toggleFeatured'])->name('profile.portfolio.toggle-featured');
    Route::post('/profile/portfolio/update-order', [ProfileController::class, 'updatePortfolioOrder'])->name('profile.portfolio.update-order');
});

// Notification routes (all authenticated users)
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
    Route::get('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    Route::delete('/read/all', [NotificationController::class, 'deleteAllRead'])->name('delete-all-read');
});

// Customer routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'home'])->name('dashboard');
    Route::get('/caterers', [CustomerController::class, 'caterers'])->name('caterers');
    
    // Caterer and package viewing
    Route::get('/caterers/{id}', [CustomerController::class, 'showCaterer'])->name('caterer.profile');
    Route::get('/caterers/{catererId}/packages/{packageId}', [CustomerController::class, 'showPackage'])->name('package.details');
    
    // Calculate custom package price
    Route::post('/calculate-price', [CustomerController::class, 'calculateCustomPrice'])->name('calculate.price');
    
    // Booking routes
    Route::post('/booking/store-event', [\App\Http\Controllers\BookingController::class, 'storeEventDetails'])->name('booking.store-event');
    Route::get('/booking/payment', [\App\Http\Controllers\BookingController::class, 'payment'])->name('booking.payment');
    Route::post('/booking/process-payment', [\App\Http\Controllers\BookingController::class, 'processPayment'])->name('booking.process-payment');
    Route::get('/booking/confirmation/{booking}', [\App\Http\Controllers\BookingController::class, 'confirmation'])->name('booking.confirmation');
    Route::get('/booking/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('booking.cancel');
    
    // View and manage bookings
    Route::get('/bookings', [CustomerController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\BookingController::class, 'show'])->name('booking.details');
    Route::patch('/bookings/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancelBooking'])->name('booking.cancel-booking');
    
    // Pay balance
    Route::get('/bookings/{booking}/pay-balance', [\App\Http\Controllers\BookingController::class, 'payBalance'])->name('booking.pay-balance');
    Route::post('/bookings/{booking}/pay-balance', [\App\Http\Controllers\BookingController::class, 'processBalancePayment'])->name('booking.process-balance');
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
    Route::get('/payments', [CustomerController::class, 'payments'])->name('payments');
    Route::get('/summary', [CustomerController::class, 'summary'])->name('summary');

    // Create review
    Route::get('/bookings/{booking}/review', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])->name('review.store');
    
    // View caterer reviews
    Route::get('/caterers/{caterer}/reviews', [ReviewController::class, 'index'])->name('caterer.reviews');

    Route::post('/check-availability', [\App\Http\Controllers\BookingController::class, 'checkAvailability'])
        ->name('booking.check-availability');
});

// Caterer routes
Route::middleware(['auth', 'role:caterer', 'caterer.approval'])->prefix('caterer')->name('caterer.')->group(function () {
    // Main caterer pages
    Route::get('/dashboard', [CatererController::class, 'dashboard'])->name('dashboard');
    
    // Calendar and Availability
    Route::get('/calendar', [CatererController::class, 'calendar'])->name('calendar');
    Route::post('/availability/toggle', [CatererController::class, 'toggleAvailability'])->name('availability.toggle');
    Route::post('/availability/block-range', [CatererController::class, 'blockDateRange'])->name('availability.block-range');
    
    // Bookings
    Route::get('/bookings', [CatererController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [CatererController::class, 'showBooking'])->name('booking.details');
    Route::patch('/bookings/{booking}/confirm', [CatererController::class, 'confirmBooking'])->name('booking.confirm');
    Route::patch('/bookings/{booking}/reject', [CatererController::class, 'rejectBooking'])->name('booking.reject');
    Route::patch('/bookings/{booking}/complete', [CatererController::class, 'completeBooking'])->name('booking.complete');
    
    Route::get('/menus', [CatererController::class, 'menus'])->name('menus');
    Route::get('/verify-receipt', [CatererController::class, 'verifyReceipt'])->name('verifyReceipt');
    Route::get('/payments', [CatererController::class, 'payments'])->name('payments');
    Route::get('/reviews', [CatererController::class, 'reviews'])->name('reviews');

    // Category management
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Menu item management
    Route::post('/menu-items', [MenuItemController::class, 'store'])->name('menu-items.store');
    Route::put('/menu-items/{menuItem}', [MenuItemController::class, 'update'])->name('menu-items.update');
    Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');

    // Package management
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::put('/packages/{package}', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
    Route::patch('/packages/{package}/toggle', [PackageController::class, 'toggle'])->name('packages.toggle');
    Route::get('/packages/{package}/items', [PackageController::class, 'getItems'])->name('packages.items');
    Route::get('/packages/{package}/price-breakdown', [PackageController::class, 'getPriceBreakdown'])->name('packages.price-breakdown');

    // View own reviews (replace existing route if present)
    Route::get('/reviews', [ReviewController::class, 'catererReviews'])->name('reviews');
    
    // Respond to reviews
    Route::post('/reviews/{review}/respond', [ReviewController::class, 'respond'])->name('reviews.respond');
    Route::post('/reviews/{review}/update-response', [ReviewController::class, 'updateResponse'])->name('reviews.update-response');
    Route::delete('/reviews/{review}/delete-response', [ReviewController::class, 'deleteResponse'])->name('reviews.delete-response');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'userManagement'])->name('users');
    Route::patch('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('users.status');
});

// Registration pending page
Route::get('/register-pending', function () {
    return view('auth.register-pending');
})->name('register.pending');

require __DIR__.'/auth.php';