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
Route::get('/onepager-settings', [ApiController::class, 'getOnepagerSettings']);
Route::get('/slides', [ApiController::class, 'getSlides']);
Route::get('/videos', [ApiController::class, 'getVideos']);
Route::get('/categories', [ApiController::class, 'getCategories']);
Route::get('/products', [ApiController::class, 'getProducts']);
Route::get('/products/{codeOrSlug}', [ApiController::class, 'getProduct']);
Route::post('/check-pincode', [ApiController::class, 'checkPincode']);
// WhatsApp OTP Authentication Endpoints
Route::post('/send-otp', [ApiController::class, 'sendOtp']);
Route::post('/auth/send-otp', [ApiController::class, 'sendOtp']);
Route::post('/verify-otp', [ApiController::class, 'verifyOtp']);
Route::post('/auth/verify-otp', [ApiController::class, 'verifyOtp']);
Route::post('/resend-otp', [ApiController::class, 'resendOtp']);
Route::post('/auth/resend-otp', [ApiController::class, 'resendOtp']);
Route::post('/login', [ApiController::class, 'login']);

// Cache Clear Helper Route
Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return response()->json(['success' => true, 'message' => 'Cache & config cleared successfully!']);
});

// Diagnostic WhatsApp Test Route
Route::get('/test-whatsapp-live', function (\Illuminate\Http\Request $request, \App\Services\WhatsAppService $service) {
    $phone = $request->query('phone', '9875411657');
    $otp = (string) mt_rand(1000, 9999);

    $token = config('services.whatsapp.access_token');
    $maskedToken = !empty($token) 
        ? substr($token, 0, 10) . '...' . substr($token, -10) . ' (Length: ' . strlen($token) . ')'
        : 'MISSING/EMPTY';

    $metaPhoneId = config('services.whatsapp.phone_number_id');
    $template = config('services.whatsapp.template_name');

    $result = $service->sendOtp($phone, $otp);

    return response()->json([
        'server_ip' => request()->server('SERVER_ADDR') ?: gethostbyname(gethostname()),
        'config_check' => [
            'meta_phone_number_id' => $metaPhoneId,
            'meta_access_token' => $maskedToken,
            'meta_template' => $template,
            'codebey_client_id' => config('services.codebey.client_id'),
        ],
        'test_phone' => $phone,
        'test_otp' => $otp,
        'send_result' => $result,
    ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

Route::get('/orders/{orderId}/chat', [ApiController::class, 'getChatMessages']);
Route::post('/orders/{orderId}/chat', [ApiController::class, 'sendChatMessage']);

// Authenticated Endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ApiController::class, 'getProfile']);
    Route::get('/addresses', [ApiController::class, 'getAddresses']);
    Route::post('/addresses', [ApiController::class, 'addAddress']);
    Route::get('/orders', [ApiController::class, 'getOrders']);
    Route::post('/orders', [ApiController::class, 'placeOrder']);
});
