<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add Price Options to Packages
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('price_silver', 10, 2)->nullable()->after('price');
            $table->decimal('price_gold', 10, 2)->nullable()->after('price_silver');
            $table->decimal('price_diamond', 10, 2)->nullable()->after('price_gold');
        });

        // 2. Track which Tier the User Bought
        Schema::table('user_packages', function (Blueprint $table) {
            $table->enum('tier', ['normal', 'silver', 'gold', 'diamond'])->default('normal')->after('package_id');
        });

        // 3. Track Tier on Ads (For fast sorting)
        Schema::table('advertisements', function (Blueprint $table) {
            $table->enum('tier', ['normal', 'silver', 'gold', 'diamond'])->default('normal')->after('status');
        });
    }

    public function down(): void
    {
        // Drop columns if rolling back
    }
};