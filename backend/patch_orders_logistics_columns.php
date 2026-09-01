<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$dbPath = config('database.connections.sqlite.database');
echo "Checking orders table columns on SQLite database: {$dbPath}\n";

$cols = Schema::getColumnListing('orders');
echo "Current orders columns: " . implode(', ', $cols) . "\n";

Schema::table('orders', function (Blueprint $table) use ($cols) {
    if (!in_array('rider_id', $cols)) {
        echo "Adding rider_id column to orders table...\n";
        $table->unsignedBigInteger('rider_id')->nullable()->after('user_id');
    }
    if (!in_array('shipment_status', $cols)) {
        echo "Adding shipment_status column to orders table...\n";
        $table->string('shipment_status')->default('Pending Assignment')->after('status');
    }
    if (!in_array('tracking_number', $cols)) {
        echo "Adding tracking_number column to orders table...\n";
        $table->string('tracking_number')->nullable()->after('shipment_status');
    }
    if (!in_array('dispatched_at', $cols)) {
        echo "Adding dispatched_at column to orders table...\n";
        $table->timestamp('dispatched_at')->nullable();
    }
    if (!in_array('delivered_at', $cols)) {
        echo "Adding delivered_at column to orders table...\n";
        $table->timestamp('delivered_at')->nullable();
    }
    if (!in_array('delivery_notes', $cols)) {
        echo "Adding delivery_notes column to orders table...\n";
        $table->text('delivery_notes')->nullable();
    }
});

echo "Orders table logistics columns verified and patched successfully!\n";
