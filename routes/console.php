<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\UserPackage;
use App\Models\Advertisement;
use App\Models\Notification;
use Carbon\Carbon;

// 1. Check for packages expiring in exactly 1 minute
Schedule::call(function () {
    $targetTime = Carbon::now()->addMinute();
    
    // Find packages expiring roughly 1 minute from now
    $expiringPackages = UserPackage::where('status', 'active')
        ->whereBetween('expires_at', [Carbon::now(), $targetTime])
        ->get();

    foreach ($expiringPackages as $pkg) {
        // Avoid duplicate notifications
        $exists = Notification::where('user_id', $pkg->user_id)
            ->where('type', 'package_expiry')
            ->where('created_at', '>', Carbon::now()->subMinutes(5))
            ->exists();

        if (!$exists) {
            Notification::create([
                'user_id' => $pkg->user_id,
                'type' => 'package_expiry',
                'message' => "Your package " . $pkg->package->name . " expires in 1 minute! Active again?",
                'data' => ['user_package_id' => $pkg->id]
            ]);
        }
    }
})->everyMinute();

// 2. Clean up Expired Items
Schedule::call(function () {
    // expire packages
    UserPackage::where('expires_at', '<', Carbon::now())
        ->where('status', '!=', 'expired')
        ->update(['status' => 'expired']);

    // expire ads linked to expired packages OR ads that timed out themselves
    Advertisement::where('expires_at', '<', Carbon::now())
        ->where('status', '!=', 'rejected')
        ->delete(); // Or set status='expired'
})->everyMinute();