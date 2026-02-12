<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\UserPackage; // <--- IMPORTANT: Need this to delete purchases first
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    // Show Dashboard with Packages
    public function index() {
        return view('welcome', ['packages' => Package::all()]);
    }

    // Show Create Form
    public function create() {
        return view('admin.packages.create');
    }

    // Store New Package (With Silver/Gold/Diamond Logic)
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image',
            'description' => 'required',
            'ad_limit' => 'required|integer',
            'validity_value' => 'required|integer',
            'duration_unit' => 'required|in:minutes,hours,days',
            // Tier Validation
            'price_silver' => 'nullable|numeric|gt:price',
            'price_gold' => 'nullable|numeric|gt:price_silver',
            'price_diamond' => 'nullable|numeric|gt:price_gold',
        ]);

        $path = $request->file('image')->store('packages', 'public');
        
        Package::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $path,
            'description' => $request->description,
            'ad_limit' => $request->ad_limit,
            'validity_value' => $request->validity_value,
            'duration_unit' => $request->duration_unit,
            // Save Tiers
            'price_silver' => $request->price_silver,
            'price_gold' => $request->price_gold,
            'price_diamond' => $request->price_diamond,
        ]);

        return redirect()->route('home')->with('success', 'Package Created Successfully');
    }

    // --- FIXED DESTROY METHOD ---
    public function destroy($id) {
        $package = Package::findOrFail($id);

        // 1. Delete purchase history first (Fixes the Foreign Key Error)
        UserPackage::where('package_id', $id)->delete();

        // 2. Delete the Image
        if($package->image) {
            Storage::disk('public')->delete($package->image);
        }

        // 3. Delete the Package
        $package->delete();

        return back()->with('success', 'Package and its history deleted successfully.');
    }
}