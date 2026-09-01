<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "riders table exists: " . (\Illuminate\Support\Facades\Schema::hasTable('riders') ? 'YES' : 'NO') . "\n";

if (\App\Models\Rider::count() == 0) {
    \App\Models\Rider::create([
        'name' => 'Ramesh Shinde',
        'phone' => '9820198201',
        'vehicle_type' => 'Motorbike',
        'vehicle_number' => 'MH-01-AX-9911',
        'operating_pincodes' => json_encode(['400001', '400002']),
        'status' => 'Available',
        'is_active' => true
    ]);
    \App\Models\Rider::create([
        'name' => 'Suresh Patil',
        'phone' => '9820298202',
        'vehicle_type' => 'EV Delivery Van',
        'vehicle_number' => 'MH-02-EV-4422',
        'operating_pincodes' => json_encode(['400003', '400004']),
        'status' => 'Available',
        'is_active' => true
    ]);
    echo "Seeded 2 riders!\n";
}

echo "riders total count: " . \App\Models\Rider::count() . "\n";
