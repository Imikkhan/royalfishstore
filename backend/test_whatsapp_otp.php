<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$phone = $argv[1] ?? '9875411657';
$otp = (string) mt_rand(1000, 9999);

echo "========================================\n";
echo "Testing WhatsApp OTP Delivery\n";
echo "Phone: {$phone}\n";
echo "OTP: {$otp}\n";
echo "========================================\n\n";

$service = app(App\Services\WhatsAppService::class);
$result = $service->sendOtp($phone, $otp);

echo "Result:\n";
print_r($result);
echo "\n========================================\n";
