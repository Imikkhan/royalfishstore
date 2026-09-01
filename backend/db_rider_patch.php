<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Rider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

$pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$stmt = $pdo->query("PRAGMA table_info(riders)");
$cols = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'name');

if (!in_array('email', $cols)) {
    echo "Adding email, password, earnings_per_delivery columns...\n";
    $pdo->exec("ALTER TABLE riders ADD COLUMN email VARCHAR(255) NULL");
    $pdo->exec("ALTER TABLE riders ADD COLUMN password VARCHAR(255) NULL");
    $pdo->exec("ALTER TABLE riders ADD COLUMN earnings_per_delivery INTEGER DEFAULT 50");
}

// Update riders with default login credentials if missing
$r1 = Rider::where('phone', '9820198201')->first();
if ($r1) {
    $r1->update([
        'email' => 'ramesh@royalfish.com',
        'password' => Hash::make('password'),
        'earnings_per_delivery' => 50
    ]);
}

$r2 = Rider::where('phone', '9820298202')->first();
if ($r2) {
    $r2->update([
        'email' => 'suresh@royalfish.com',
        'password' => Hash::make('password'),
        'earnings_per_delivery' => 50
    ]);
}

echo "Riders authentication patched successfully!\n";
