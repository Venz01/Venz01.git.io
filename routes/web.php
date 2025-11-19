<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CatererController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\PackageController;
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
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'home'])->name('dashboard');
    Route::get('/caterers', [CustomerController::class, 'caterers'])->name('caterers');
    
    // Caterer and package viewing
    Route::get('/caterers/{id}', [CustomerController::class, 'showCaterer'])->name('caterer.profile');
    Route::get('/caterers/{catererId}/packages/{packageId}', [CustomerController::class, 'showPackage'])->name('package.details');
    
    // Calculate custom package price
    Route::post('/calculate-price', [CustomerController::class, 'calculateCustomPrice'])->name('calculate.price');
    
    Route::get('/bookings', [CustomerController::class, 'bookings'])->name('bookings');
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
    Route::get('/payments', [CustomerController::class, 'payments'])->name('payments');
    Route::get('/notifications', [CustomerController::class, 'notifications'])->name('notifications');
    Route::get('/summary', [CustomerController::class, 'summary'])->name('summary');
});

// Caterer routes
Route::middleware(['auth', 'role:caterer', 'caterer.approval'])->prefix('caterer')->name('caterer.')->group(function () {
    // Main caterer pages
    Route::get('/dashboard', [CatererController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [CatererController::class, 'bookings'])->name('bookings');
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