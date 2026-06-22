<?php

use Illuminate\Support\Facades\Route;

// Public APIs
Route::get('categories', [App\Http\Controllers\Api\CategoryController::class, 'index']);
Route::get('categories/{id}', [App\Http\Controllers\Api\CategoryController::class, 'show']);
Route::get('products/search', [App\Http\Controllers\Api\ProductController::class, 'index']);
Route::get('products', [App\Http\Controllers\Api\ProductController::class, 'index']);
Route::get('products/{id}', [App\Http\Controllers\Api\ProductController::class, 'show']);
Route::get('products/{id}/reviews', [App\Http\Controllers\Api\ReviewController::class, 'index']);

// Auth APIs
Route::post('register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::middleware('web')->post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);

// Protected APIs
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('user', [App\Http\Controllers\Api\AuthController::class, 'user']);

    // Profile
    Route::get('profile', [App\Http\Controllers\Api\ProfileController::class, 'show']);
    Route::put('profile', [App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::put('change-password', [App\Http\Controllers\Api\ProfileController::class, 'changePassword']);

    // Wishlist
    Route::get('wishlists', [App\Http\Controllers\Api\WishlistController::class, 'index']);
    Route::post('wishlists', [App\Http\Controllers\Api\WishlistController::class, 'store']);
    Route::delete('wishlists/{id}', [App\Http\Controllers\Api\WishlistController::class, 'destroy']);

    // Cart
    Route::get('carts', [App\Http\Controllers\Api\CartController::class, 'index']);
    Route::post('carts', [App\Http\Controllers\Api\CartController::class, 'store']);
    Route::put('carts/{id}', [App\Http\Controllers\Api\CartController::class, 'update']);
    Route::delete('carts/{id}', [App\Http\Controllers\Api\CartController::class, 'destroy']);

    // Orders
    Route::get('orders', [App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::post('orders', [App\Http\Controllers\Api\OrderController::class, 'store']);
    Route::get('orders/{id}', [App\Http\Controllers\Api\OrderController::class, 'show']);

    // Reviews
    Route::post('reviews', [App\Http\Controllers\Api\ReviewController::class, 'store']);

    // Admin
    Route::prefix('admin')->group(function () {
        Route::get('stats', [App\Http\Controllers\Api\Admin\DashboardController::class, 'stats']);
        Route::apiResource('categories', App\Http\Controllers\Api\Admin\CategoryController::class);
        Route::apiResource('products', App\Http\Controllers\Api\Admin\ProductController::class);
        Route::get('orders', [App\Http\Controllers\Api\Admin\OrderController::class, 'index']);
        Route::get('orders/{id}', [App\Http\Controllers\Api\Admin\OrderController::class, 'show']);
        Route::put('orders/{id}/status', [App\Http\Controllers\Api\Admin\OrderController::class, 'updateStatus']);
        Route::get('users', [App\Http\Controllers\Api\Admin\UserController::class, 'index']);
    });
});
