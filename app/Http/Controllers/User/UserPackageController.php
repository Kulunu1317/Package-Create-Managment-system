<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\UserPackage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserPackageController extends Controller
{
    public function buy(Package $package) {
        $user = Auth::user();
        
        // 2-Hour Limit Logic
        $lastPurchase = UserPackage::where('user_id', $user->id)
            ->latest('created_at')
            ->first();

        if ($lastPurchase && $lastPurchase->created_at->diffInHours(now()) < 2) {
            return back()->with('error', 'Only one package can be bought within 2 hours!!');
        }

        UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'expires_at' => Carbon::now()->addDays($package->validity_days),
        ]);

        return back()->with('success', 'Package Bought Successfully!');
    }

    public function index() {
        $myPackages = UserPackage::where('user_id', Auth::id())
            ->with('package')
            ->where('expires_at', '>', now()) // Auto remove expired logic
            ->get();
        return view('user.my_packages', compact('myPackages'));
    }
}