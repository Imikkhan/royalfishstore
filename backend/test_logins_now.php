<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Rider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "=================== LOGIN VERIFICATION ===================\n";

// 1. Check Super Admin Login
$adminSuccess = Auth::attempt(['email' => 'admin@royalfish.com', 'password' => 'password']);
echo "1. Super Admin (admin@royalfish.com / password): " . ($adminSuccess ? "✅ LOGIN SUCCESSFUL" : "❌ LOGIN FAILED") . "\n";

// 2. Check Rider Ramesh Login
$rider1Success = Auth::guard('rider')->attempt(['email' => 'ramesh@royalfish.com', 'password' => 'password']);
echo "2. Rider 1 (ramesh@royalfish.com / password): " . ($rider1Success ? "✅ LOGIN SUCCESSFUL" : "❌ LOGIN FAILED") . "\n";

// 3. Check Rider Suresh Login
$rider2Success = Auth::guard('rider')->attempt(['email' => 'suresh@royalfish.com', 'password' => 'password']);
echo "3. Rider 2 (suresh@royalfish.com / password): " . ($rider2Success ? "✅ LOGIN SUCCESSFUL" : "❌ LOGIN FAILED") . "\n";

echo "==========================================================\n";
