<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'stock_quantity')) {
                $table->integer('stock_quantity')->default(50)->after('is_active');
            }
            if (!Schema::hasColumn('products', 'in_stock')) {
                $table->boolean('in_stock')->default(true)->after('stock_quantity');
            }
            if (!Schema::hasColumn('products', 'low_stock_threshold')) {
                $table->integer('low_stock_threshold')->default(5)->after('in_stock');
            }
            if (!Schema::hasColumn('products', 'min_order_qty')) {
                $table->integer('min_order_qty')->default(1)->after('low_stock_threshold');
            }
            if (!Schema::hasColumn('products', 'max_order_qty')) {
                $table->integer('max_order_qty')->default(10)->after('min_order_qty');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock_quantity', 'in_stock', 'low_stock_threshold', 'min_order_qty', 'max_order_qty']);
        });
    }
};
