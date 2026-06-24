<?php

use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Admin Auth
Route::prefix('admin')->group(function () {
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

    // Protected admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories.index');
        Route::get('categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('categories/{id}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        Route::get('products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products.index');
        Route::get('products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('admin.products.create');
        Route::post('products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');
        Route::get('products/{id}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.products.update');
        Route::delete('products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.products.destroy');

        Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
        Route::put('orders/{id}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update');

        Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');

        // Notifications
        Route::get('notifications/unread-count', [App\Http\Controllers\Admin\NotificationController::class, 'unreadCount'])->name('admin.notifications.unread-count');
        Route::get('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notifications.index');
        Route::post('notifications/{id}/read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
        Route::post('notifications/read-all', [App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('admin.notifications.read-all');
    });
});
