<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CatererController;
use App\Http\Controllers\AdminController;
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
});

// Customer routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'home'])->name('customer.dashboard');
    Route::get('/customer/caterers', [CustomerController::class, 'browseCaterers'])->name('customer.caterers');
    Route::get('/customer/bookings', [CustomerController::class, 'bookings'])->name('customer.bookings');
    Route::get('/customer/cart', [CustomerController::class, 'cart'])->name('customer.cart');
    Route::get('/customer/payments', [CustomerController::class, 'payments'])->name('customer.payments');
    Route::get('/customer/notifications', [CustomerController::class, 'notifications'])->name('customer.notifications');
    Route::get('/customer/summary', [CustomerController::class, 'summary'])->name('customer.summary');
});

// Caterer routes
Route::middleware(['auth', 'role:caterer', 'caterer.approval'])->group(function () {
    Route::get('/caterer/dashboard', [CatererController::class, 'dashboard'])->name('caterer.dashboard');
    Route::get('/caterer/bookings', [CatererController::class, 'bookings'])->name('caterer.bookings');
    Route::get('/caterer/menus', [CatererController::class, 'menus'])->name('caterer.menus');
    Route::get('/caterer/packages', [CatererController::class, 'packages'])->name('caterer.packages');
    Route::get('/caterer/verify-receipt', [CatererController::class, 'verifyReceipt'])->name('caterer.verifyReceipt');
    Route::get('/caterer/payments', [CatererController::class, 'payments'])->name('caterer.payments');
    Route::get('/caterer/reviews', [CatererController::class, 'reviews'])->name('caterer.reviews');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'userManagement'])->name('admin.users');
    Route::patch('/admin/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('admin.users.status');
});

Route::get('/register-pending', function () {
    return view('auth.register-pending');
})->name('register.pending');


require __DIR__.'/auth.php';
