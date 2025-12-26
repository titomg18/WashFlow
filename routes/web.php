<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// ==================
// GUEST (BELUM LOGIN)
// ==================
Route::middleware('guest')->group(function () {
    // Halaman login (GET)
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    
    // Proses login (POST) - INI YANG HILANG!
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    // Halaman register (GET)
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    
    // Proses register (POST)
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// ==================
// AUTH (SUDAH LOGIN)
// ==================
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Orders
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/paid', [OrderController::class, 'markAsPaid'])->name('orders.paid');

    // Customers
    Route::resource('customers', CustomerController::class)->except(['edit', 'update', 'destroy']);
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/staff', [AdminController::class, 'staffManagement'])->name('staff');
        Route::get('/services', [AdminController::class, 'serviceManagement'])->name('services');
    });
});
