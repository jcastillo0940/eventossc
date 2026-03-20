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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        Schema::table('event_judges', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('event_judges', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
