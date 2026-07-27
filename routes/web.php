<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;

Route::get('/', [ProductController::class, 'index'])->name('home');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    
    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');
    Route::post('/orders/{order}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::post('/orders/{order}/confirm-cod', [OrderController::class, 'confirmCOD'])->name('orders.confirm-cod');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
    
    // Review routes
    Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('review.store');
});

// Product detail (bisa diakses tanpa login)
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');