<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->string('name');
                $table->string('avatar')->nullable();
                $table->tinyInteger('rating')->default(5);
                $table->text('quote');
                $table->string('product_tag')->nullable();
                $table->json('images')->nullable();
                $table->string('video_url')->nullable();
                $table->boolean('is_verified')->default(true);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });

            // Seed default reviews with photos & video
            $defaultReviews = [
                [
                    'name' => 'Moumita Sen',
                    'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80',
                    'rating' => 5,
                    'quote' => 'Prawns were very fresh and big size. Delivery was under 35 minutes and the iced packing kept them completely chilled. Will definitely order every Sunday!',
                    'product_tag' => 'Golda Prawns - 500g',
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?auto=format&fit=crop&w=600&q=80'
                    ]),
                    'video_url' => 'https://www.youtube.com/shorts/3jXfQ8z90jA',
                    'is_verified' => true,
                    'is_active' => true,
                    'sort_order' => 1,
                    'created_at' => now()->subDays(2),
                    'updated_at' => now()->subDays(2),
                ],
                [
                    'name' => 'Rajesh Kumar',
                    'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
                    'rating' => 5,
                    'quote' => 'Rohu fish cut was super clean, no smell at all. Bengali cut pieces were perfect for jhol. The rider was polite and on time.',
                    'product_tag' => 'Rohu (Rui) Fish - 1kg',
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1534483509719-3feaee7c30da?auto=format&fit=crop&w=600&q=80'
                    ]),
                    'video_url' => null,
                    'is_verified' => true,
                    'is_active' => true,
                    'sort_order' => 2,
                    'created_at' => now()->subDays(3),
                    'updated_at' => now()->subDays(3),
                ],
                [
                    'name' => 'Sunita Roy',
                    'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=150&q=80',
                    'rating' => 5,
                    'quote' => 'Diamond Harbour Hilsa order kiya tha. Fish ekdum fresh thi, pet er tel chilo bohot shundor! Pure traditional aroma. Must try for fish lovers.',
                    'product_tag' => 'Fresh Hilsa (Ilish) - 1kg Cut',
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80'
                    ]),
                    'video_url' => 'https://www.youtube.com/shorts/5qap5aO4i9A',
                    'is_verified' => true,
                    'is_active' => true,
                    'sort_order' => 3,
                    'created_at' => now()->subDays(4),
                    'updated_at' => now()->subDays(4),
                ],
                [
                    'name' => 'Vikramaditya Rao',
                    'avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=150&q=80',
                    'rating' => 5,
                    'quote' => 'Surmai steaks and Jumbo tiger prawns were delivered in just 30 minutes! Cleaned so well that I just had to marinate and fry. 10/10 quality and packing.',
                    'product_tag' => 'Surmai Steaks & Tiger Prawns',
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1535400255456-984241443b29?auto=format&fit=crop&w=600&q=80'
                    ]),
                    'video_url' => null,
                    'is_verified' => true,
                    'is_active' => true,
                    'sort_order' => 4,
                    'created_at' => now()->subDays(5),
                    'updated_at' => now()->subDays(5),
                ],
                [
                    'name' => 'Debolina Banerjee',
                    'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                    'rating' => 5,
                    'quote' => 'Bhetki fillet was so fresh, made Kolkata style fish fry for guests and everyone asked where I got the fish from! Very pleased with Royal Fish Store.',
                    'product_tag' => 'Bhetki Fish Fillet - 500g',
                    'images' => json_encode([
                        'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80'
                    ]),
                    'video_url' => 'https://www.youtube.com/shorts/4yKq09h32wQ',
                    'is_verified' => true,
                    'is_active' => true,
                    'sort_order' => 5,
                    'created_at' => now()->subDays(6),
                    'updated_at' => now()->subDays(6),
                ]
            ];

            foreach ($defaultReviews as $rev) {
                DB::table('reviews')->insert($rev);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
