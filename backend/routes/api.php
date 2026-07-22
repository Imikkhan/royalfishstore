<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Endpoints
Route::get('/settings', [ApiController::class, 'getSettings']);
Route::get('/slides', [ApiController::class, 'getSlides']);
Route::get('/categories', [ApiController::class, 'getCategories']);
Route::get('/products', [ApiController::class, 'getProducts']);
Route::get('/products/{codeOrSlug}', [ApiController::class, 'getProduct']);
Route::post('/check-pincode', [ApiController::class, 'checkPincode']);
Route::post('/login', [ApiController::class, 'login']);

// Authenticated Endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ApiController::class, 'getProfile']);
    Route::get('/addresses', [ApiController::class, 'getAddresses']);
    Route::post('/addresses', [ApiController::class, 'addAddress']);
    Route::get('/orders', [ApiController::class, 'getOrders']);
    Route::post('/orders', [ApiController::class, 'placeOrder']);
});
