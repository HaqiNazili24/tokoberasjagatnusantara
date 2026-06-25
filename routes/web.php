<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer;
use App\Http\Controllers\Owner;
use App\Http\Controllers\Karyawan;
use App\Http\Controllers\Kurir;
use Illuminate\Support\Facades\Route;

// ===== PUBLIC / CUSTOMER =====
Route::get('/', [Customer\ShopController::class, 'home'])->name('home');
Route::get('/products/{slug}', [Customer\ShopController::class, 'show'])->name('products.show');
Route::get('/search', [Customer\ShopController::class, 'search'])->name('search');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Customer area
Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/cart', [Customer\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [Customer\CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cart}', [Customer\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [Customer\CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [Customer\CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [Customer\CheckoutController::class, 'place'])->name('checkout.place');

    Route::get('/orders', [Customer\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [Customer\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/upload-proof', [Customer\OrderController::class, 'uploadProof'])->name('orders.upload-proof');
    Route::post('/orders/{order}/cancel', [Customer\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/received', [Customer\OrderController::class, 'received'])->name('orders.received');
    
    // Rating & Ulasan
    Route::post('/orders/{order}/review', [Customer\OrderController::class, 'storeReview'])->name('orders.review');
});

// ===== OWNER =====
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/', [Owner\OwnerController::class, 'index'])->name('dashboard');
    
    // Kelola Akun Karyawan & Kurir
    Route::get('/users', [Owner\OwnerController::class, 'usersIndex'])->name('users.index');
    Route::get('/users/create', [Owner\OwnerController::class, 'usersCreate'])->name('users.create');
    Route::post('/users', [Owner\OwnerController::class, 'usersStore'])->name('users.store');
    Route::get('/users/{user}/edit', [Owner\OwnerController::class, 'usersEdit'])->name('users.edit');
    Route::put('/users/{user}', [Owner\OwnerController::class, 'usersUpdate'])->name('users.update');
    Route::delete('/users/{user}', [Owner\OwnerController::class, 'usersDestroy'])->name('users.destroy');
    
    // Kelola Produk (Tambah, Edit, Hapus)
    Route::resource('products', Owner\ProductController::class)->except(['show']);
    
    // Activity Log / Audit Log
    Route::get('/audit-logs', [Owner\OwnerController::class, 'auditLogs'])->name('audit-logs');
});

// ===== KARYAWAN =====
Route::middleware(['auth', 'karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/', [Karyawan\KaryawanController::class, 'index'])->name('dashboard');
    Route::post('/products/{product}/stock', [Karyawan\KaryawanController::class, 'updateStock'])->name('products.stock');
    Route::get('/orders/{order}', [Karyawan\KaryawanController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{order}/confirm-payment', [Karyawan\KaryawanController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::post('/orders/{order}/reject-payment', [Karyawan\KaryawanController::class, 'rejectPayment'])->name('orders.reject-payment');
    Route::post('/orders/{order}/update-status', [Karyawan\KaryawanController::class, 'updateOrderStatus'])->name('orders.update-status');
});

// ===== KURIR =====
Route::middleware(['auth', 'kurir'])->prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/', [Kurir\KurirController::class, 'index'])->name('dashboard');
    Route::get('/orders/{order}', [Kurir\KurirController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [Kurir\KurirController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/proof', [Kurir\KurirController::class, 'uploadProof'])->name('orders.proof');
    Route::post('/orders/{order}/confirm-cod', [Kurir\KurirController::class, 'confirmCodPayment'])->name('orders.confirm-cod');
});