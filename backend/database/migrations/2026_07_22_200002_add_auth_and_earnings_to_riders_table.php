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
        Schema::table('riders', function (Blueprint $table) {
            $table->string('email')->nullable()->unique()->after('phone');
            $table->string('password')->nullable()->after('email');
            $table->integer('earnings_per_delivery')->default(50)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riders', function (Blueprint $table) {
            $table->dropColumn(['email', 'password', 'earnings_per_delivery']);
        });
    }
};
