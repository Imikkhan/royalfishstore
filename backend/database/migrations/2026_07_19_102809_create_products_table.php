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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('sub_category')->nullable();
            $table->string('image')->nullable();
            $table->integer('price');
            $table->integer('original_price')->nullable();
            $table->string('weight')->nullable();
            $table->string('pieces')->nullable();
            $table->string('servings')->nullable();
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->float('rating')->default(5.0);
            $table->integer('reviews_count')->default(0);
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_today_special')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
