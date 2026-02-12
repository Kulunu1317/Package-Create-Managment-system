<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\UserPackage;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function index() {
        $admin = User::where('role', 'admin')->first();
        $notifications = Notification::where('user_id', $admin->id)->latest()->get();
        return view('admin.notifications', compact('notifications'));
    }

    public function approveExtension($id) {
        $ad = Advertisement::findOrFail($id);
        $currentExpiry = Carbon::parse($ad->expires_at);
        
        if($ad->extension_unit == 'minutes') $newExpiry = $currentExpiry->addMinutes($ad->extension_value);
        elseif($ad->extension_unit == 'hours') $newExpiry = $currentExpiry->addHours($ad->extension_value);
        else $newExpiry = $currentExpiry->addDays($ad->extension_value);

        $ad->update(['expires_at' => $newExpiry, 'extension_requested_at' => null]);

        Notification::create([
            'user_id' => $ad->user_id,
            'type' => 'admin_response',
            'message' => "Time Approved Success! Ad extended.",
        ]);

        return back()->with('success', 'Time Extension Approved');
    }

    // *** THIS IS THE MISSING LOGIC ***
    public function rejectExtension($id) {
        $ad = Advertisement::findOrFail($id);

        // Clear request WITHOUT extending time
        $ad->update([
            'extension_requested_at' => null,
            'extension_value' => null,
            'extension_unit' => null
        ]);

        Notification::create([
            'user_id' => $ad->user_id,
            'type' => 'admin_response',
            'message' => "Your Time Expand Request Rejected!",
        ]);

        return back()->with('success', 'Time Extension Rejected');
    }

    public function approveRenewal($id) {
        $userPackage = UserPackage::with('package')->findOrFail($id);
        $newExpiry = Carbon::now();
        
        if($userPackage->package->duration_unit == 'minutes') $newExpiry->addMinutes($userPackage->package->validity_value);
        elseif($userPackage->package->duration_unit == 'hours') $newExpiry->addHours($userPackage->package->validity_value);
        else $newExpiry->addDays($userPackage->package->validity_value);

        $userPackage->update(['status' => 'active', 'expires_at' => $newExpiry, 'renewal_requested_at' => null]);

        Notification::create([
            'user_id' => $userPackage->user_id,
            'type' => 'admin_response',
            'message' => "Package Reactivated!",
        ]);

        return back()->with('success', 'Package Reactivated');
    }
}