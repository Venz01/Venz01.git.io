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
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// ============================================
// 🏠 PUBLIC ROUTES (No Login Required)
// ============================================

// Landing Page - Guest users can browse caterers
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// ============================================
// 🔐 AUTHENTICATED ROUTES (Login Required)
// ============================================

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

// Terms and Conditions Page
Route::get('/terms-and-conditions', function () {
    return view('terms-and-conditions');
})->name('terms');

// Profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile photo route (separate from profile update)
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    
    // Portfolio management (Caterers only)
    Route::post('/profile/portfolio/upload', [ProfileController::class, 'uploadPortfolio'])->name('profile.portfolio.upload');
    Route::delete('/profile/portfolio/{id}', [ProfileController::class, 'deletePortfolio'])->name('profile.portfolio.delete');
    Route::patch('/profile/portfolio/{id}/toggle-featured', [ProfileController::class, 'toggleFeatured'])->name('profile.portfolio.toggle-featured');
    Route::post('/profile/portfolio/update-order', [ProfileController::class, 'updatePortfolioOrder'])->name('profile.portfolio.update-order');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/portfolio/{id}', [ProfileController::class, 'deletePortfolio'])->name('profile.portfolio.delete');
    Route::patch('/profile/portfolio/order', [ProfileController::class, 'updatePortfolioOrder'])->name('profile.portfolio.order');
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
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    Route::get('/payments', [CustomerController::class, 'payments'])->name('payments');
    Route::get('/summary', [CustomerController::class, 'summary'])->name('summary');

    // Create review
    Route::get('/bookings/{booking}/review', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])->name('review.store');
    
    // View caterer reviews
    Route::get('/caterers/{caterer}/reviews', [ReviewController::class, 'index'])->name('caterer.reviews');

    Route::post('/check-availability', [\App\Http\Controllers\BookingController::class, 'checkAvailability'])
        ->name('booking.check-availability');

    // Orders Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/cart', [OrderController::class, 'cart'])->name('orders.cart');
    Route::get('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/process', [OrderController::class, 'processOrder'])->name('orders.process');
    Route::get('/orders/{order}/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    
    // Cart Operations
    Route::post('/orders/cart/add/{menuItem}', [OrderController::class, 'addToCart'])->name('orders.add-to-cart');
    Route::patch('/orders/cart/update/{menuItem}', [OrderController::class, 'updateCart'])->name('orders.update-cart');
    Route::delete('/orders/cart/remove/{menuItem}', [OrderController::class, 'removeFromCart'])->name('orders.remove-from-cart');
    Route::delete('/orders/cart/clear', [OrderController::class, 'clearCart'])->name('orders.clear-cart');
});

// Caterer routes - Apply caterer.suspended middleware
Route::middleware(['auth', 'role:caterer', 'caterer.suspended', 'caterer.approval'])->prefix('caterer')->name('caterer.')->group(function () {
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

    // Display menu management (for customer viewing)
    Route::post('/display-menus', [\App\Http\Controllers\DisplayMenuController::class, 'store'])->name('display-menus.store');
    Route::put('/display-menus/{displayMenu}', [\App\Http\Controllers\DisplayMenuController::class, 'update'])->name('display-menus.update');
    Route::delete('/display-menus/{displayMenu}', [\App\Http\Controllers\DisplayMenuController::class, 'destroy'])->name('display-menus.destroy');
    Route::patch('/display-menus/{displayMenu}/toggle-status', [\App\Http\Controllers\DisplayMenuController::class, 'toggleStatus'])->name('display-menus.toggle-status');

    // Package management
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::put('/packages/{package}', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
    Route::patch('/packages/{package}/toggle', [PackageController::class, 'toggle'])->name('packages.toggle');
    Route::get('/packages/{package}/items', [PackageController::class, 'getItems'])->name('packages.items');
    Route::get('/packages/{package}/price-breakdown', [PackageController::class, 'getPriceBreakdown'])->name('packages.price-breakdown');

    // View own reviews
    Route::get('/reviews', [ReviewController::class, 'catererReviews'])->name('reviews');
    
    // Respond to reviews
    Route::post('/reviews/{review}/respond', [ReviewController::class, 'respond'])->name('reviews.respond');
    Route::post('/reviews/{review}/update-response', [ReviewController::class, 'updateResponse'])->name('reviews.update-response');
    Route::delete('/reviews/{review}/delete-response', [ReviewController::class, 'deleteResponse'])->name('reviews.delete-response');

    Route::post('/bulk-action', [CatererController::class, 'bulkAction'])->name('bulk-action');

    Route::get('/orders', [CatererController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [CatererController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/status', [CatererController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/confirm-payment', [CatererController::class, 'confirmPayment'])->name('orders.confirm-payment');

});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'userManagement'])->name('users');
    Route::patch('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('users.status');
    
    // Add these caterer management routes
    Route::get('/caterers/{caterer}', [AdminController::class, 'showCaterer'])->name('caterers.show');
// Change from PATCH to POST
Route::post('/caterers/{caterer}/approve', [AdminController::class, 'approveCaterer'])->name('caterers.approve');
Route::post('/caterers/{caterer}/reject', [AdminController::class, 'rejectCaterer'])->name('caterers.reject');

    // Activity Logs
    Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');

    // Activity Logs Detail Route (for AJAX)
    Route::get('/admin/activity-logs/{id}', [AdminController::class, 'getActivityLogDetails'])
    ->name('admin.activity-log-details')
    ->middleware(['auth', 'admin']);
});

Route::middleware(['auth'])->prefix('caterer')->name('caterer.')->group(function () {
    
    // Main reports page
    Route::get('/reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports');
    
    // Export routes
    Route::get('/reports/export/pdf', [App\Http\Controllers\ReportsController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/reports/export/excel', [App\Http\Controllers\ReportsController::class, 'exportExcel'])->name('reports.excel');
    
});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'userManagement'])->name('users');
    
    // 🆕 ADD THESE NEW ROUTES HERE
    Route::get('/feedback-ratings', [AdminController::class, 'feedbackRatings'])->name('feedback-ratings');
    Route::get('/feedback-ratings/{review}', [AdminController::class, 'showReview'])->name('feedback-ratings.show');
    Route::patch('/feedback-ratings/{review}/approve', [AdminController::class, 'approveReview'])->name('feedback-ratings.approve');
    Route::patch('/feedback-ratings/{review}/flag', [AdminController::class, 'flagReview'])->name('feedback-ratings.flag');
    Route::patch('/feedback-ratings/{review}/remove', [AdminController::class, 'removeReview'])->name('feedback-ratings.remove');
    Route::patch('/feedback-ratings/{review}/restore', [AdminController::class, 'restoreReview'])->name('feedback-ratings.restore');
    Route::post('/feedback-ratings/bulk-action', [AdminController::class, 'bulkReviewAction'])->name('feedback-ratings.bulk-action');
    
    Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... existing routes ...
    
    // Feedback & Ratings Routes
    Route::get('/feedback-ratings', [App\Http\Controllers\AdminController::class, 'feedbackRatings'])->name('feedback-ratings');
    Route::get('/feedback-ratings/{review}', [App\Http\Controllers\AdminController::class, 'showReview'])->name('feedback-ratings.show');
    Route::patch('/feedback-ratings/{review}/approve', [App\Http\Controllers\AdminController::class, 'approveReview'])->name('feedback-ratings.approve');
    Route::patch('/feedback-ratings/{review}/flag', [App\Http\Controllers\AdminController::class, 'flagReview'])->name('feedback-ratings.flag');
    Route::patch('/feedback-ratings/{review}/remove', [App\Http\Controllers\AdminController::class, 'removeReview'])->name('feedback-ratings.remove');
    Route::patch('/feedback-ratings/{review}/restore', [App\Http\Controllers\AdminController::class, 'restoreReview'])->name('feedback-ratings.restore');
    Route::post('/feedback-ratings/bulk-action', [App\Http\Controllers\AdminController::class, 'bulkReviewAction'])->name('feedback-ratings.bulk-action');
});




// Registration pending page
Route::get('/register-pending', function () {
    return view('auth.register-pending');
})->name('register.pending');

require __DIR__.'/auth.php';
