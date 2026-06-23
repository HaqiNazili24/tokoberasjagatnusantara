<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer;
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
});

// ===== SETUP (HAPUS SETELAH ADMIN BERHASIL DIBUAT) =====
Route::get('/setup-admin-x9k2p', function () {
    $secret = request('key');
    if ($secret !== 'jagatnusantara2024') {
        abort(403, 'Forbidden');
    }

    \App\Models\User::updateOrCreate(
        ['email' => 'admin@jagatnusantara.test'],
        [
            'full_name' => 'Admin Jagat Nusantara',
            'phone'     => '081234567890',
            'password'  => bcrypt('admin123'),
            'role'      => 'admin',
        ]
    );

    return response()->json(['status' => 'Akun admin berhasil dibuat!', 'email' => 'admin@jagatnusantara.test', 'password' => 'admin123']);
});

// ===== ADMIN =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('sub-categories', Admin\SubCategoryController::class)->only(['index', 'store', 'update', 'destroy'])->parameters(['sub-categories' => 'subCategory']);
    Route::resource('products', Admin\ProductController::class)->except(['show']);

    Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/confirm-payment', [Admin\OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::post('orders/{order}/reject-payment', [Admin\OrderController::class, 'rejectPayment'])->name('orders.reject-payment');
    Route::post('orders/{order}/update-status', [Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');

    Route::get('reports', [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/pdf', [Admin\ReportController::class, 'pdf'])->name('reports.pdf');
});