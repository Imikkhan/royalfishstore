<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use App\Models\Product;

echo "Checking products table schema...\n";

Schema::table('products', function (Blueprint $table) {
    if (!Schema::hasColumn('products', 'stock_quantity')) {
        $table->integer('stock_quantity')->default(50)->after('is_active');
        echo "Added column: stock_quantity\n";
    } else {
        echo "Column already exists: stock_quantity\n";
    }

    if (!Schema::hasColumn('products', 'in_stock')) {
        $table->boolean('in_stock')->default(true)->after('stock_quantity');
        echo "Added column: in_stock\n";
    } else {
        echo "Column already exists: in_stock\n";
    }

    if (!Schema::hasColumn('products', 'low_stock_threshold')) {
        $table->integer('low_stock_threshold')->default(5)->after('in_stock');
        echo "Added column: low_stock_threshold\n";
    } else {
        echo "Column already exists: low_stock_threshold\n";
    }

    if (!Schema::hasColumn('products', 'min_order_qty')) {
        $table->integer('min_order_qty')->default(1)->after('low_stock_threshold');
        echo "Added column: min_order_qty\n";
    } else {
        echo "Column already exists: min_order_qty\n";
    }

    if (!Schema::hasColumn('products', 'max_order_qty')) {
        $table->integer('max_order_qty')->default(10)->after('min_order_qty');
        echo "Added column: max_order_qty\n";
    } else {
        echo "Column already exists: max_order_qty\n";
    }
});

// Initialize existing products stock values if null
DB::table('products')->whereNull('stock_quantity')->update(['stock_quantity' => 50]);
DB::table('products')->whereNull('in_stock')->update(['in_stock' => 1]);
DB::table('products')->whereNull('low_stock_threshold')->update(['low_stock_threshold' => 5]);
DB::table('products')->whereNull('min_order_qty')->update(['min_order_qty' => 1]);
DB::table('products')->whereNull('max_order_qty')->update(['max_order_qty' => 10]);

// Ensure default settings exist for minimum order amount
Setting::firstOrCreate(
    ['key' => 'min_order_amount'],
    ['value' => '199']
);

Setting::firstOrCreate(
    ['key' => 'free_delivery_threshold'],
    ['value' => '499']
);

echo "Products and Settings database patch completed successfully!\n";
echo "Total Products: " . Product::count() . "\n";
