<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{
    public function create(UserPackage $userPackage) {
        if (!$userPackage->isValid()) {
            return redirect()->route('user.my_packages')->with('error', 'Package invalid or limit reached');
        }
        return view('user.create_ad', compact('userPackage'));
    }

    public function store(Request $request) {
        $request->validate([
            'job_name' => 'required', 'job_type' => 'required',
            'company_logo' => 'required|image', 'salary' => 'required',
            'description' => 'required', 'user_package_id' => 'required'
        ]);

        $package = UserPackage::findOrFail($request->user_package_id);
        
        // Final Safety Check
        if ($package->ads_posted >= $package->package->ad_limit) {
             return back()->with('error', 'Limit Reached');
        }

        $path = $request->file('company_logo')->store('logos', 'public');

        Advertisement::create([
            'user_id' => Auth::id(),
            'user_package_id' => $package->id,
            'job_name' => $request->job_name,
            'job_type' => $request->job_type,
            'company_logo' => $path,
            'salary' => $request->salary,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        $package->increment('ads_posted');

        return redirect()->route('home')->with('success', 'Ad sent to admin for approval');
    }

    // Admin Methods
    public function adminIndex() {
        $ads = Advertisement::with('user')->where('status', 'pending')->get();
        return view('admin.ads_index', compact('ads'));
    }

    public function approve($id) {
        Advertisement::findOrFail($id)->update(['status' => 'approved']);
        return back()->with('success', 'Approved');
    }

    public function reject($id) {
        Advertisement::findOrFail($id)->update(['status' => 'rejected']);
        return back()->with('success', 'Rejected');
    }
}