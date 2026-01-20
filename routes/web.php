<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

// Static & Main Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/collections', [CollectionController::class, 'index'])->name('collections');
Route::get('/about', fn() => view('about'))->name('about');

// Shop & Product
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Cart
Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/add', 'add')->name('add');
    Route::patch('/update', 'update')->name('update');
    Route::delete('/remove', 'remove')->name('remove');
    Route::post('/coupon', 'applyCoupon')->name('coupon.apply');
    Route::post('/coupon/remove', 'removeCoupon')->name('coupon.remove');
});

// Checkout
Route::controller(CheckoutController::class)->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::get('/success/{orderNumber}', 'success')->name('success');
});

// Orders
// Orders
Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{order}/review', [App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
Route::post('/orders/{order}/review', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth
    Route::get('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');

    // Dashboard (Protected)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Profile
        Route::get('/profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('/password', [App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('password.update');
    });
});
