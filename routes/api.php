<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\AdminOrderController;
use App\Http\Controllers\Api\AdminProductController;
use App\Http\Controllers\Api\AdminSettingsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Products (public read access)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Categories (public read access)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Orders (authenticated users only)
    Route::apiResource('orders', OrderController::class);

    // Admin only routes
    Route::middleware('admin')->group(function () {
        // Dashboard
        Route::get('/admin/dashboard/stats', [DashboardController::class, 'getStats']);
        Route::get('/admin/dashboard/activities', [DashboardController::class, 'getRecentActivities']);
        Route::get('/admin/dashboard/system-health', [DashboardController::class, 'getSystemHealth']);

        // User Management
        Route::get('/admin/users', [AdminUserController::class, 'index']);
        Route::post('/admin/users', [AdminUserController::class, 'store']);
        Route::get('/admin/users/{id}', [AdminUserController::class, 'show']);
        Route::put('/admin/users/{id}', [AdminUserController::class, 'update']);
        Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy']);
        Route::post('/admin/users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus']);
        Route::get('/admin/users-stats', [AdminUserController::class, 'getUserStats']);

        // Order Management
        Route::get('/admin/orders', [AdminOrderController::class, 'index']);
        Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show']);
        Route::put('/admin/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);
        Route::put('/admin/orders/{id}/payment-status', [AdminOrderController::class, 'updatePaymentStatus']);
        Route::delete('/admin/orders/{id}', [AdminOrderController::class, 'destroy']);
        Route::get('/admin/orders-stats', [AdminOrderController::class, 'getOrderStats']);
        Route::get('/admin/orders/export', [AdminOrderController::class, 'export']);

        // Product Management (Admin)
        Route::get('/admin/products', [AdminProductController::class, 'index']);
        Route::post('/admin/products', [AdminProductController::class, 'store']);
        Route::put('/admin/products/{id}', [AdminProductController::class, 'update']);
        Route::delete('/admin/products/{id}', [AdminProductController::class, 'destroy']);
        Route::post('/admin/products/{id}/toggle-status', [AdminProductController::class, 'toggleStatus']);
        Route::post('/admin/products/bulk-action', [AdminProductController::class, 'bulkAction']);
        Route::get('/admin/products-stats', [AdminProductController::class, 'getProductStats']);
        Route::post('/admin/products/import', [AdminProductController::class, 'import']);

        // Category Management
        Route::post('/admin/categories', [CategoryController::class, 'store']);
        Route::put('/admin/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy']);

        // Settings Management
        Route::get('/admin/settings', [AdminSettingsController::class, 'getSettings']);
        Route::put('/admin/settings', [AdminSettingsController::class, 'updateSettings']);
        Route::post('/admin/cache/clear', [AdminSettingsController::class, 'clearCache']);
        Route::get('/admin/system-info', [AdminSettingsController::class, 'getSystemInfo']);

        // Backup Management
        Route::post('/admin/backup/create', [AdminSettingsController::class, 'createBackup']);
        Route::get('/admin/backups', [AdminSettingsController::class, 'getBackups']);
        Route::get('/admin/backup/{filename}/download', [AdminSettingsController::class, 'downloadBackup']);
        Route::delete('/admin/backup/{filename}', [AdminSettingsController::class, 'deleteBackup']);
    });
});
