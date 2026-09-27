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
        if (!Schema::hasTable('delivery_pincodes')) {
            Schema::create('delivery_pincodes', function (Blueprint $table) {
                $table->id();
                $table->string('pincode', 6)->unique();
                $table->string('area_name')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });

            // Seed the delivery hubs that were previously hardcoded on the website so the
            // customer experience stays identical until an admin edits the list.
            $defaults = [
                ['pincode' => '700156', 'area_name' => 'Action Area I (New Town)', 'is_active' => true, 'sort_order' => 1],
                ['pincode' => '700136', 'area_name' => 'Action Area II / Chinar Park', 'is_active' => true, 'sort_order' => 2],
                ['pincode' => '700160', 'area_name' => 'Action Area III (New Town)', 'is_active' => true, 'sort_order' => 3],
                ['pincode' => '700135', 'area_name' => 'Rajarhat / DLF 1 & 2', 'is_active' => true, 'sort_order' => 4],
                ['pincode' => '700091', 'area_name' => 'Sector V / Salt Lake IT Hub', 'is_active' => true, 'sort_order' => 5],
                ['pincode' => '700064', 'area_name' => 'Salt Lake (Sector I, II, III)', 'is_active' => true, 'sort_order' => 6],
                ['pincode' => '700010', 'area_name' => 'Ultadanga / Kankurgachi', 'is_active' => true, 'sort_order' => 7],
                ['pincode' => '700107', 'area_name' => 'EM Bypass / Ruby', 'is_active' => true, 'sort_order' => 8],
            ];

            foreach ($defaults as $row) {
                $row['created_at'] = now();
                $row['updated_at'] = now();
                DB::table('delivery_pincodes')->insert($row);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_pincodes');
    }
};
