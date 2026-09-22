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
Route::get('/serviceable-pincodes', [ApiController::class, 'getServiceablePincodes']);
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

// Diagnostic WhatsApp Order Confirmation Test Route
Route::get('/test-whatsapp-order', function (\Illuminate\Http\Request $request, \App\Services\WhatsAppService $service) {
    $phone = $request->query('phone', '9875411657');
    $name = $request->query('name', 'Valued Customer');
    $orderId = 'ROYAL-' . mt_rand(100000, 999999);
    $items = '1x Fresh Hilsa / Ilish, 1x Tiger Prawns';
    $total = '₹1,250.00';
    $payment = 'COD';
    $address = 'Flat 402, Royal Residency, Marine Drive, Kolkata 700001';

    $templateParams = [$name, $orderId, $items, $total, $payment, $address];
    $result = $service->sendMessage(
        $phone,
        "Order #{$orderId} placed successfully!",
        'order_confirmation',
        $templateParams,
        '1904807637591601'
    );

    return response()->json([
        'test_type' => 'Order Confirmation WhatsApp Notification',
        'target_phone' => $phone,
        'order_id' => $orderId,
        'result' => $result
    ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

// Diagnostic WhatsApp Order Status Update Test Route
Route::get('/test-whatsapp-status', function (\Illuminate\Http\Request $request, \App\Services\WhatsAppService $service) {
    $phone = $request->query('phone', '9875411657');
    $name = $request->query('name', 'Valued Customer');
    $orderId = 'ROYAL-' . mt_rand(100000, 999999);
    $status = $request->query('status', 'Out for Delivery');
    $slot = '30-45 mins';
    $address = 'Flat 402, Royal Residency, Marine Drive, Kolkata 700001';

    $templateParams = [$name, $orderId, $status, $slot, $address];
    $result = $service->sendMessage(
        $phone,
        "Order #{$orderId} status updated to {$status}!",
        'order_status_update',
        $templateParams,
        '28074372922185765'
    );

    return response()->json([
        'test_type' => 'Order Status Update WhatsApp Notification',
        'target_phone' => $phone,
        'order_id' => $orderId,
        'new_status' => $status,
        'result' => $result
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
