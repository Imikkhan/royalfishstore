<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Rider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "--- ADMIN LOGIN CHECK ---\n";
$admin = User::where('email', 'admin@royalfish.com')->first();
if ($admin) {
    echo "Admin User Found! ID: {$admin->id}, Email: {$admin->email}\n";
    $checkPass = Hash::check('password', $admin->password);
    echo "Password check ('password'): " . ($checkPass ? 'MATCH' : 'FAILED') . "\n";
    $attempt = Auth::attempt(['email' => 'admin@royalfish.com', 'password' => 'password']);
    echo "Auth::attempt: " . ($attempt ? 'SUCCESS' : 'FAILED') . "\n";
} else {
    echo "Admin User NOT found!\n";
}

echo "\n--- RIDER LOGIN CHECK ---\n";
$rider = Rider::where('email', 'ramesh@royalfish.com')->first();
if ($rider) {
    echo "Rider Found! ID: {$rider->id}, Email: {$rider->email}\n";
    $checkRiderPass = Hash::check('password', $rider->password);
    echo "Password check ('password'): " . ($checkRiderPass ? 'MATCH' : 'FAILED') . "\n";
    $riderAttempt = Auth::guard('rider')->attempt(['email' => 'ramesh@royalfish.com', 'password' => 'password']);
    echo "Auth::guard('rider')->attempt: " . ($riderAttempt ? 'SUCCESS' : 'FAILED') . "\n";
} else {
    echo "Rider NOT found!\n";
}
