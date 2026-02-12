<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\UserPackage;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{
    // 1. Show "Create Ad" Form
    public function create(UserPackage $userPackage) {
        // Check if package is valid (Active + Future Date + Ad Slots available)
        if (!$userPackage->isValid()) {
            return redirect()->route('user.my_packages')
                ->with('error', 'Error: Package invalid, expired, or ad limit reached.');
        }
        return view('user.create_ad', compact('userPackage'));
    }

    // 2. Store the Ad in Database
    public function store(Request $request) {
        // A. Validate the incoming data
        $request->validate([
            'job_name' => 'required|string|max:255',
            'job_type' => 'required|string',
            'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'salary' => 'required|string',
            'description' => 'required|string',
            'user_package_id' => 'required|exists:user_packages,id'
        ]);

        $package = UserPackage::findOrFail($request->user_package_id);

        // B. Check ad limit again for safety
        if ($package->ads_posted >= $package->package->ad_limit) {
             return back()->with('error', 'Ad limit reached for this package.');
        }

        // C. Upload the Logo
        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('logos', 'public');
        } else {
            return back()->with('error', 'Image upload failed');
        }

        // D. Create the Advertisement
        Advertisement::create([
            'user_id' => Auth::id(),
            'user_package_id' => $package->id,
            'job_name' => $request->job_name,
            'job_type' => $request->job_type,
            'company_logo' => $path,
            'salary' => $request->salary,
            'description' => $request->description,
            
            // Critical: Copy the TIER from the package so sorting works
            'tier' => $package->tier, 
            
            'status' => 'pending', // Default status is pending approval
            'expires_at' => $package->expires_at, // Sync expiry with package
        ]);

        // E. Increment the counter on the package
        $package->increment('ads_posted');

        return redirect()->route('home')->with('success', 'Ad submitted! Waiting for admin approval.');
    }

    // 3. User Requests Time Extension
    public function updateTime(Request $request, $id) {
        $request->validate([
            'extension_value' => 'required|integer|min:1',
            'extension_unit' => 'required|in:minutes,hours,days'
        ]);

        $ad = Advertisement::findOrFail($id);
        
        // Save request details to the ad
        $ad->update([
            'extension_requested_at' => now(),
            'extension_value' => $request->extension_value,
            'extension_unit' => $request->extension_unit
        ]);

        // Notify Admin
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'ad_extension_request',
                'message' => "User " . Auth::user()->name . " requests time extension for ad: " . $ad->job_name,
                'data' => ['ad_id' => $id]
            ]);
        }

        return back()->with('success', 'Time extension request sent to Admin');
    }

    // --- Admin Methods ---

    // 4. View Pending Ads
    public function adminIndex() {
        $ads = Advertisement::with('user')->where('status', 'pending')->latest()->get();
        return view('admin.ads_index', compact('ads'));
    }

    // 5. Approve Ad
    public function approve($id) {
        Advertisement::findOrFail($id)->update(['status' => 'approved']);
        return back()->with('success', 'Ad Approved Successfully');
    }

    // 6. Reject Ad
    public function reject($id) {
        Advertisement::findOrFail($id)->update(['status' => 'rejected']);
        return back()->with('success', 'Ad Rejected');
    }
}