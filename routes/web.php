<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerController::class, 'shop'])->name('shop');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
Route::get('/admin/orders', [AdminController::class, 'ordersPage'])->name('admin.orders');
Route::get('/admin/users', [AdminController::class, 'usersPage'])->name('admin.users');
Route::get('/admin/logs', [AdminController::class, 'logsPage'])->name('admin.logs');
Route::get('/api/categories', [AdminController::class, 'categories']);
Route::post('/api/categories', [AdminController::class, 'storeCategory']);
Route::put('/api/categories/{id}', [AdminController::class, 'updateCategory']);
Route::delete('/api/categories/{id}', [AdminController::class, 'deleteCategory']);
Route::get('/api/products', [AdminController::class, 'products']);
Route::post('/api/products', [AdminController::class, 'storeProduct']);
Route::put('/api/products/{id}', [AdminController::class, 'updateProduct']);
Route::delete('/api/products/{id}', [AdminController::class, 'deleteProduct']);
Route::get('/api/orders', [AdminController::class, 'orders']);
Route::put('/api/orders/{id}/status', [AdminController::class, 'updateOrderStatus']);
Route::get('/api/users', [AdminController::class, 'users']);
Route::post('/api/users', [AdminController::class, 'storeUser']);
Route::put('/api/users/{id}', [AdminController::class, 'updateUser']);
Route::delete('/api/users/{id}', [AdminController::class, 'deleteUser']);
Route::get('/api/logs', [AdminController::class, 'logs']);
Route::get('/api/shop-products', [CustomerController::class, 'products']);
Route::post('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
Route::get('/my-orders', [CustomerController::class, 'myOrders'])->name('my.orders');
Route::get('/api/my-orders', [CustomerController::class, 'myOrdersData']);
