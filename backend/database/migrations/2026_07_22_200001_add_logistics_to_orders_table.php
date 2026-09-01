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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('rider_id')->nullable()->after('user_id');
            $table->string('shipment_status')->default('Pending Assignment')->after('status');
            $table->string('tracking_number')->nullable()->after('shipment_status');
            $table->timestamp('dispatched_at')->nullable()->after('tracking_number');
            $table->timestamp('delivered_at')->nullable()->after('dispatched_at');
            $table->text('delivery_notes')->nullable()->after('delivered_at');

            $table->foreign('rider_id')->references('id')->on('riders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['rider_id']);
            $table->dropColumn([
                'rider_id',
                'shipment_status',
                'tracking_number',
                'dispatched_at',
                'delivered_at',
                'delivery_notes'
            ]);
        });
    }
};
