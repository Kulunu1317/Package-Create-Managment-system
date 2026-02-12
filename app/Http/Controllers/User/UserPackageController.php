<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\UserPackage;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserPackageController extends Controller
{
    // --- 1. SHOW MY PACKAGES (This was missing!) ---
    public function index() {
        // Fetch packages belonging to the logged-in user
        $myPackages = UserPackage::where('user_id', Auth::id())
            ->with('package') // Load package details (name, image, etc)
            ->latest()
            ->get();

        return view('user.my_packages', compact('myPackages'));
    }

    // --- 2. BUY PACKAGE ---
  public function buy(Request $request, Package $package) {
        $user = Auth::user();
        
        // 1. Get Selected Tier from Form (default to normal)
        $tier = $request->input('tier', 'normal');

        // 2. Calculate Expiry
        $expiresAt = \Carbon\Carbon::now();
        if($package->duration_unit == 'minutes') $expiresAt->addMinutes($package->validity_value);
        elseif($package->duration_unit == 'hours') $expiresAt->addHours($package->validity_value);
        else $expiresAt->addDays($package->validity_value);

        // 3. Create UserPackage with the correct TIER
        UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'tier' => $tier, // <--- SAVING THE TIER
            'expires_at' => $expiresAt,
            'status' => 'active'
        ]);

        return back()->with('success', "Successfully bought {$package->name} ({$tier} Package)!");
    }

    // --- 3. REQUEST RENEWAL (Active Again) ---
    public function requestRenewal($id) {
        $userPackage = UserPackage::findOrFail($id);
        
        $userPackage->update([
            'status' => 'pending_renewal', 
            'renewal_requested_at' => now()
        ]);

        // Notify Admin
        $admin = User::where('role', 'admin')->first();
        if($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'renewal_request',
                'message' => "User " . Auth::user()->name . " requests renewal for package: " . $userPackage->package->name,
                'data' => ['user_package_id' => $id]
            ]);
        }

        return back()->with('success', 'Renewal request sent to Admin!');
    }
}