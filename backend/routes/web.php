<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Redirect root to Admin Dashboard
Route::get('/', function () {
    return redirect('/admin/dashboard');
});

// Admin Panel Group
Route::prefix('admin')->group(function () {
    
    // Guest authentication routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminController::class, 'login']);
    });

    // Authenticated admin routes
    Route::middleware('auth')->group(function () {
        Route::get('/logout', [AdminController::class, 'logout']);
        Route::get('/dashboard', [AdminController::class, 'dashboard']);

        // Roles & Permissions CRUD (AJAX)
        Route::get('/roles', [AdminController::class, 'rolesIndex']);
        Route::post('/roles/store', [AdminController::class, 'rolesStore']);
        Route::post('/roles/update/{id}', [AdminController::class, 'rolesUpdate']);
        Route::post('/roles/delete', [AdminController::class, 'rolesDelete']);

        // Users & Staff CRUD (AJAX)
        Route::get('/users', [AdminController::class, 'usersIndex']);
        Route::post('/users/store', [AdminController::class, 'usersStore']);
        Route::post('/users/update/{id}', [AdminController::class, 'usersUpdate']);
        Route::post('/users/delete', [AdminController::class, 'usersDelete']);

        // Categories CRUD (AJAX)
        Route::get('/categories', [AdminController::class, 'categoriesIndex']);
        Route::post('/categories/store', [AdminController::class, 'categoriesStore']);
        Route::post('/categories/update/{id}', [AdminController::class, 'categoriesUpdate']);
        Route::post('/categories/delete', [AdminController::class, 'categoriesDelete']);
        Route::post('/categories/toggle-status', [AdminController::class, 'categoriesToggleStatus']);

        // Products CRUD (AJAX)
        Route::get('/products', [AdminController::class, 'productsIndex']);
        Route::post('/products/store', [AdminController::class, 'productsStore']);
        Route::post('/products/update/{id}', [AdminController::class, 'productsUpdate']);
        Route::post('/products/delete', [AdminController::class, 'productsDelete']);
        Route::post('/products/toggle-status', [AdminController::class, 'productsToggleStatus']);

        // Orders Tracking (AJAX)
        Route::get('/orders', [AdminController::class, 'ordersIndex']);
        Route::get('/orders/details/{id}', [AdminController::class, 'orderDetails']);
        Route::post('/orders/update-status/{id}', [AdminController::class, 'ordersUpdateStatus']);
        Route::post('/orders/delete', [AdminController::class, 'ordersDelete']);

        // Media Manager (AJAX)
        Route::get('/media', [AdminController::class, 'mediaIndex']);
        Route::post('/media/upload', [AdminController::class, 'mediaUpload']);
        Route::post('/media/delete/{id}', [AdminController::class, 'mediaDelete']);

        // Settings Module
        Route::get('/settings', [AdminController::class, 'settingsIndex']);
        Route::post('/settings/save', [AdminController::class, 'settingsSave']);

        // Hero Banners / Slides (AJAX)
        Route::get('/slides', [AdminController::class, 'slidesIndex']);
        Route::post('/slides/store', [AdminController::class, 'slidesStore']);
        Route::post('/slides/update/{id}', [AdminController::class, 'slidesUpdate']);
        Route::post('/slides/delete', [AdminController::class, 'slidesDelete']);
        Route::post('/slides/toggle-status', [AdminController::class, 'slidesToggleStatus']);
    });
});
