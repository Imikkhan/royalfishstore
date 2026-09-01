<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Rider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

$out = [];
$out[] = "Riders Count: " . Rider::count();

$riders = Rider::all();
foreach ($riders as $r) {
    $out[] = "ID: {$r->id} | Name: {$r->name} | Email: '{$r->email}' | Phone: {$r->phone}";
    $hashCheck = Hash::check('password', $r->password);
    $out[] = "  -> Hash check for 'password': " . ($hashCheck ? "MATCH" : "FAIL");
}

$attemptRamesh = Auth::guard('rider')->attempt(['email' => 'ramesh@royalfish.com', 'password' => 'password']);
$out[] = "Auth::guard('rider')->attempt('ramesh@royalfish.com', 'password'): " . ($attemptRamesh ? "SUCCESS" : "FAILED");

file_put_contents('debug_res.txt', implode("\n", $out));
