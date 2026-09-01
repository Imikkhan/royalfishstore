<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Rider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

$lines = [];
$lines[] = "DB DATABASE ENV: " . env('DB_DATABASE');
$lines[] = "Riders count in DB: " . Rider::count();

foreach (Rider::all() as $r) {
    $match = Hash::check('password', $r->password) ? 'YES' : 'NO';
    $lines[] = "ID: {$r->id} | Name: {$r->name} | Email: {$r->email} | Active: {$r->is_active} | Pass 'password' match: {$match}";
}

$attempt = Auth::guard('rider')->attempt(['email' => 'ramesh@royalfish.com', 'password' => 'password']);
$lines[] = "Auth::guard('rider')->attempt for ramesh@royalfish.com / password: " . ($attempt ? "SUCCESS" : "FAILED");

file_put_contents('C:/Users/HP/royal-fish-store/backend/rider_db_output.txt', implode("\n", $lines));
