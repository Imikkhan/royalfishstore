<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$chat = \App\Models\OrderChat::create([
    'order_id' => 'ROYAL-361198',
    'sender_type' => 'rider',
    'sender_id' => 1,
    'sender_name' => 'Ramesh Shinde (Rider)',
    'message' => 'Hello! I am on my way with your fish order. 🚴',
]);

$all = \App\Models\OrderChat::all()->toArray();
file_put_contents('c:/Users/HP/royal-fish-store/backend/chat_log.json', json_encode($all, JSON_PRETTY_PRINT));
