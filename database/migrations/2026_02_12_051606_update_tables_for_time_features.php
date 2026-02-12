<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update Packages (Add Duration Unit)
        Schema::table('packages', function (Blueprint $table) {
            $table->enum('duration_unit', ['minutes', 'hours', 'days'])->default('days')->after('validity_days');
            $table->renameColumn('validity_days', 'validity_value'); // Rename for clarity
        });

        // 2. Update User Packages (Status & Renewal)
        Schema::table('user_packages', function (Blueprint $table) {
            $table->enum('status', ['active', 'expired', 'pending_renewal'])->default('active');
            $table->timestamp('renewal_requested_at')->nullable();
        });

        // 3. Update Advertisements (Expiry & Extension)
        Schema::table('advertisements', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable(); // Ad has its own expiry now
            $table->timestamp('extension_requested_at')->nullable();
            $table->integer('extension_value')->nullable();
            $table->enum('extension_unit', ['minutes', 'hours', 'days'])->nullable();
        });

        // 4. Create Notifications Table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'package_expiry', 'renewal_request', 'ad_extension_request', 'admin_response'
            $table->text('message');
            $table->json('data')->nullable(); // Stores related IDs (package_id, ad_id)
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Rollback logic (omitted for brevity)
    }
};