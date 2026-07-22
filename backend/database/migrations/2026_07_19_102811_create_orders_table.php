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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary(); // ROYAL-123456 format
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('total_price');
            $table->string('payment_method');
            $table->json('address_data'); // Snapshot of address
            $table->string('status')->default('Placed'); // Placed, Processing, Dispatched, Delivered, Cancelled
            $table->string('estimated_delivery')->default('30-45 mins');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
