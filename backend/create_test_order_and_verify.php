<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Rider;
use App\Models\User;

// 1. Get or create test customer
$user = User::first();

// 2. Create fresh sample order
$order = Order::create([
    'user_id' => $user ? $user->id : 1,
    'total_price' => 599,
    'status' => 'Pending',
    'payment_method' => 'COD',
    'payment_status' => 'Pending',
    'address_data' => [
        'name' => 'Rajesh Sharma',
        'phone' => '9876543210',
        'addressLine' => 'Flat 302, Sea View Apartments, Marine Drive',
        'city' => 'Mumbai',
        'state' => 'Maharashtra',
        'zipCode' => '400001'
    ],
    'shipment_status' => 'Pending Assignment'
]);

// Add Order Item
OrderItem::create([
    'order_id' => $order->id,
    'product_id' => 1,
    'product_name' => 'Surmai / Seer King Fish Steaks (500g)',
    'quantity' => 1,
    'price' => 599
]);

echo "Fresh Test Order Created! Order ID: {$order->id}\n";

// Assign to Ramesh
$ramesh = Rider::where('phone', '9820198201')->first();
if ($ramesh) {
    $order->rider_id = $ramesh->id;
    $order->shipment_status = 'Dispatched';
    $order->status = 'Dispatched';
    $order->tracking_number = 'RF-TRK-' . rand(100000, 999999);
    $order->dispatched_at = now();
    $order->save();

    $ramesh->status = 'On Delivery';
    $ramesh->save();

    echo "Assigned Order #{$order->id} to Ramesh Shinde (Rider ID: {$ramesh->id}) successfully!\n";
}
