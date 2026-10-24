<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController; // Ini sudah benar

/*
|--------------------------------------------------------------------------
| Web Routes - TechCatalog E-Commerce
|--------------------------------------------------------------------------
*/

// =====================================================
// 1. AUTENTIKASI
// =====================================================
Auth::routes();

// =====================================================
// 2. PUBLIK (Bisa diakses tanpa login)
// =====================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{product}', [HomeController::class, 'show'])->name('products.show');

// =====================================================
// 3. USER WAJIB LOGIN (Cart, Checkout, Riwayat)
// =====================================================
Route::middleware('auth')->group(function () {

    // Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CartController::class, 'checkoutPage'])->name('checkout.index');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');

    // Pembelian Instan & Nota
    Route::post('/products/{product}/buy', [OrderController::class, 'directBuy'])->name('orders.direct');
    Route::get('/orders/{order_number}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');

    // Riwayat Pesanan Saya
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');

    // Batalkan Pesanan
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
});

// =====================================================
// 4. ADMIN PANEL (Wajib Login + Role Admin)
// =====================================================
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Produk (CRUD) - HANYA GUNAKAN INI
    Route::resource('products', AdminProductController::class);

    // Pesanan
    Route::get('orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::patch('orders/{order}/status', [OrderController::class, 'adminUpdateStatus'])->name('orders.updateStatus');

}); // <-- Tutup group admin di sini