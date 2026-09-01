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
        Schema::create('order_chats', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('sender_type'); // 'rider' or 'customer'
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->string('sender_name')->nullable();
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_chats');
    }
};
