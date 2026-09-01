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
        if (!Schema::hasTable('videos')) {
            Schema::create('videos', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('youtube_url');
                $table->string('youtube_id');
                $table->string('thumbnail')->nullable();
                $table->string('duration')->default('1:00');
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });

            // Seed initial 2 sample videos
            DB::table('videos')->insert([
                [
                    'title' => 'Daily Fresh Catch From Local Waters',
                    'youtube_url' => 'https://www.youtube.com/watch?v=LXb3EKWsInQ',
                    'youtube_id' => 'LXb3EKWsInQ',
                    'thumbnail' => 'https://img.youtube.com/vi/LXb3EKWsInQ/hqdefault.jpg',
                    'duration' => '0:45',
                    'is_active' => true,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Prawns Cleaning & Vacuum Packing Process',
                    'youtube_url' => 'https://www.youtube.com/watch?v=3JZ_D3ELwOQ',
                    'youtube_id' => '3JZ_D3ELwOQ',
                    'thumbnail' => 'https://img.youtube.com/vi/3JZ_D3ELwOQ/hqdefault.jpg',
                    'duration' => '0:38',
                    'is_active' => true,
                    'sort_order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
