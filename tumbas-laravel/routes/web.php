<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DapurController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\AdminController;

// ─── Halaman Pelanggan ───────────────────────────────────────────────
Route::get('/', [MenuController::class, 'index'])->name('menu');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/qris/{id}', [OrderController::class, 'qris'])->name('order.qris');
Route::get('/order/track/{id}', [OrderController::class, 'track'])->name('order.track');

// ─── Auth ─────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Panel Staff (butuh login) ────────────────────────────────────────
Route::middleware('auth.staff')->group(function () {

    // Panel Dapur
    Route::get('/dapur', [DapurController::class, 'index'])->name('dapur');
    Route::post('/dapur/status', [DapurController::class, 'updateStatus'])->name('dapur.status');

    // Panel Kasir
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir');
    Route::post('/kasir/status', [KasirController::class, 'updateStatus'])->name('kasir.status');
    Route::post('/kasir/bayar', [KasirController::class, 'toggleBayar'])->name('kasir.bayar');
    Route::post('/kasir/stok', [KasirController::class, 'toggleStok'])->name('kasir.stok');
    Route::delete('/kasir/hapus/{id}', [KasirController::class, 'hapus'])->name('kasir.hapus');

    // API cek order baru (untuk notifikasi kasir)
    Route::get('/api/check-orders', [KasirController::class, 'checkNewOrders'])->name('api.check');

    // --- MANAJEMEN CRUD (Admin Only) ---
    Route::prefix('admin')->name('admin.')->group(function() {
        // Menu CRUD
        Route::get('/menus', [AdminController::class, 'menuIndex'])->name('menus.index');
        Route::get('/menus/create', [AdminController::class, 'menuCreate'])->name('menus.create');
        Route::post('/menus', [AdminController::class, 'menuStore'])->name('menus.store');
        Route::get('/menus/{id}/edit', [AdminController::class, 'menuEdit'])->name('menus.edit');
        Route::post('/menus/{id}', [AdminController::class, 'menuUpdate'])->name('menus.update');
        Route::delete('/menus/{id}', [AdminController::class, 'menuDelete'])->name('menus.delete');

        // User CRUD
        Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'userCreate'])->name('users.create');
        Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'userEdit'])->name('users.edit');
        Route::post('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'userDelete'])->name('users.delete');

        // Order CRUD
        Route::get('/orders', [AdminController::class, 'orderIndex'])->name('orders.index');
        Route::delete('/orders/{id}', [AdminController::class, 'orderDelete'])->name('orders.delete');
    });
});

