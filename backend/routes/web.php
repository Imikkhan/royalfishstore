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
        Route::post('/send-otp', [AdminController::class, 'sendOtp']);
        Route::post('/verify-otp', [AdminController::class, 'verifyOtp']);
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
        Route::post('/categories/update-order', [AdminController::class, 'categoriesUpdateOrder']);

        // Products CRUD (AJAX)
        Route::get('/products', [AdminController::class, 'productsIndex']);
        Route::post('/products/store', [AdminController::class, 'productsStore']);
        Route::post('/products/update/{id}', [AdminController::class, 'productsUpdate']);
        Route::post('/products/delete', [AdminController::class, 'productsDelete']);
        Route::post('/products/toggle-status', [AdminController::class, 'productsToggleStatus']);

        // Stock & Inventory Management (AJAX)
        Route::get('/inventory', [AdminController::class, 'inventoryIndex']);
        Route::post('/inventory/update-stock', [AdminController::class, 'inventoryUpdateStock']);
        Route::post('/inventory/toggle-stock', [AdminController::class, 'inventoryToggleStock']);
        Route::post('/inventory/bulk-update', [AdminController::class, 'inventoryBulkUpdate']);

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

        // Facebook Ad Page Manage
        Route::get('/facebook-ad', [AdminController::class, 'facebookAdIndex']);
        Route::post('/facebook-ad/save', [AdminController::class, 'facebookAdSave']);

        // Hero Banners / Slides (AJAX)
        Route::get('/slides', [AdminController::class, 'slidesIndex']);
        Route::post('/slides/store', [AdminController::class, 'slidesStore']);
        Route::post('/slides/update/{id}', [AdminController::class, 'slidesUpdate']);
        Route::post('/slides/delete', [AdminController::class, 'slidesDelete']);

        // Videos CRUD (AJAX)
        Route::get('/videos', [AdminController::class, 'videosIndex']);
        Route::post('/videos/store', [AdminController::class, 'videosStore']);
        Route::post('/videos/update/{id}', [AdminController::class, 'videosUpdate']);
        Route::post('/videos/delete', [AdminController::class, 'videosDelete']);
        Route::post('/videos/toggle-status', [AdminController::class, 'videosToggleStatus']);
        // Logistics & Shipping Management
        Route::get('/logistics', [AdminController::class, 'logisticsIndex']);
        Route::get('/logistics/riders', [AdminController::class, 'ridersIndex']);
        Route::post('/logistics/riders/store', [AdminController::class, 'ridersStore']);
        Route::post('/logistics/riders/update/{id}', [AdminController::class, 'ridersUpdate']);
        Route::post('/logistics/riders/delete', [AdminController::class, 'ridersDelete']);
        Route::post('/logistics/riders/toggle-status', [AdminController::class, 'ridersToggleStatus']);
        Route::post('/logistics/assign-rider', [AdminController::class, 'assignRider']);
        Route::post('/logistics/update-shipment-status', [AdminController::class, 'updateShipmentStatus']);
    });
});

// Delivery Rider Portal Group
Route::prefix('rider')->group(function () {
    Route::get('/login', [\App\Http\Controllers\RiderController::class, 'showLogin'])->name('rider.login');
    Route::post('/login', [\App\Http\Controllers\RiderController::class, 'login']);
    Route::post('/send-otp', [\App\Http\Controllers\RiderController::class, 'sendOtp']);
    Route::post('/verify-otp', [\App\Http\Controllers\RiderController::class, 'verifyOtp']);
    Route::get('/logout', [\App\Http\Controllers\RiderController::class, 'logout']);

    Route::get('/dashboard', [\App\Http\Controllers\RiderController::class, 'dashboard']);
    Route::post('/update-status', [\App\Http\Controllers\RiderController::class, 'updateDeliveryStatus']);
    Route::post('/toggle-duty', [\App\Http\Controllers\RiderController::class, 'toggleDuty']);
    Route::get('/chat/{orderId}', [\App\Http\Controllers\RiderController::class, 'getChatMessages']);
    Route::post('/chat/{orderId}', [\App\Http\Controllers\RiderController::class, 'sendChatMessage']);
});

// Helper utility routes for Server Storage Link & Cache Clear
Route::get('/run-storage-link', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $target = storage_path('app/public');
        $shortcut = public_path('storage');
        if (!file_exists($shortcut) && function_exists('symlink')) {
            @symlink($target, $shortcut);
        }
        return response()->json(['success' => true, 'message' => 'Storage link created successfully!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
});

Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return response()->json(['success' => true, 'message' => 'Cache & config cleared successfully!']);
});

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

Route::get('/test-order-whatsapp', function (\Illuminate\Http\Request $request, \App\Services\WhatsAppService $service) {
    $phone = $request->query('phone', '9875411657');
    $type = $request->query('type', 'order'); // 'order', 'status', 'rider', or 'all'

    $results = [];

    // 1. Test Order Confirmation
    if ($type === 'order' || $type === 'all') {
        $results['order_confirmation'] = $service->sendMessage(
            $phone,
            "🎉 *Order Placed Successfully!*\n\nDear *Valued Customer*, thank you for ordering with *Royal Fish Store*! 🐟\n\n📋 *Order ID:* #ROYAL-TEST\n🛍️ *Items:* 1x Fresh Hilsa 1kg\n💰 *Total Amount:* ₹1,499.00\n💳 *Payment Mode:* Cash on Delivery\n📍 *Delivery Address:* Kolkata, West Bengal\n\nWe are packing your order!",
            'order_confirmation',
            ['Valued Customer', 'ROYAL-TEST', '1x Fresh Hilsa 1kg', '₹1,499.00', 'Cash on Delivery', 'Kolkata, West Bengal'],
            '1904807637591601'
        );
    }

    // 2. Test Order Status Update
    if ($type === 'status' || $type === 'all') {
        $results['order_status_update'] = $service->sendMessage(
            $phone,
            "📦 *Order Status Update*\n\nDear *Valued Customer*, your Royal Fish Store order *#ROYAL-TEST* status has been updated to:\n👉 *Out for Delivery* 👈\n\n⏱️ *Estimated Delivery:* 30-45 mins\n📍 *Delivery Address:* Kolkata, West Bengal\n\nThank you for choosing Royal Fish Store! 🐟",
            'order_status_update',
            ['Valued Customer', 'ROYAL-TEST', 'Out for Delivery', '30-45 mins', 'Kolkata, West Bengal'],
            '28074372922185765'
        );
    }

    // 3. Test Rider Assignment
    if ($type === 'rider' || $type === 'all') {
        $results['rider_order_assigned'] = $service->sendMessage(
            $phone,
            "🛵 *NEW DELIVERY ASSIGNMENT!* 📦\n\nHello *Ramesh Kumar*, a new order *#ROYAL-TEST* has been assigned to you for delivery!\n\n👤 *Customer:* Rahul Sharma\n📞 *Phone:* +91 98765 43210\n📍 *Delivery Address:* Salt Lake, Kolkata\n💵 *Amount to Collect:* ₹1,499 (Cash on Delivery)\n🛍️ *Items:* 1x Fresh Hilsa 1kg\n\n⚡ Please start delivery!",
            'rider_order_assigned',
            ['Ramesh Kumar', 'ROYAL-TEST', 'Rahul Sharma', '+91 98765 43210', 'Salt Lake, Kolkata', '₹1,499 (Cash on Delivery)', '1x Fresh Hilsa 1kg'],
            '1061770773101675'
        );
    }

    return response()->json([
        'message' => 'Test WhatsApp messages triggered!',
        'target_phone' => $phone,
        'results' => $results
    ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

