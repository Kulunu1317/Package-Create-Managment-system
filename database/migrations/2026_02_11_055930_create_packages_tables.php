<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Packages created by Admin
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->text('description');
            $table->integer('ad_limit'); // Number of ads allowed
            $table->integer('validity_days'); // Validity in days
            $table->timestamps();
        });

        // 2. Packages purchased by Users
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->timestamp('expires_at');
            $table->integer('ads_posted')->default(0);
            $table->timestamps();
        });

        // 3. Advertisements posted by Users
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_package_id')->constrained('user_packages')->onDelete('cascade');
            $table->string('job_name');
            $table->string('job_type'); // Full-time, Part-time
            $table->string('company_logo')->nullable();
            $table->string('salary');
            $table->text('description');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
        Schema::dropIfExists('user_packages');
        Schema::dropIfExists('packages');
    }
};