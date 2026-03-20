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
        Schema::create('brand_event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Migrate existing data (Brand has event_id)
        $brands = DB::table('brands')->get();
        foreach ($brands as $brand) {
            if ($brand->event_id) {
                DB::table('brand_event')->insert([
                    'brand_id' => $brand->id,
                    'event_id' => $brand->event_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Remove event_id from brands
        Schema::table('brands', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
        });

        Schema::dropIfExists('brand_event');
    }
};
