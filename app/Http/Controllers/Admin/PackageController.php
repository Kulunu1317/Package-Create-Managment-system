<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
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

    // Store New Package
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required',
            'ad_limit' => 'required|integer',
            
            // Validate the NEW fields
            'validity_value' => 'required|integer',
            'duration_unit' => 'required|in:minutes,hours,days',
        ]);

        $path = $request->file('image')->store('packages', 'public');
        
        Package::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $path,
            'description' => $request->description,
            'ad_limit' => $request->ad_limit,
            
            // Save the NEW fields
            'validity_value' => $request->validity_value,
            'duration_unit' => $request->duration_unit,
        ]);

        return redirect()->route('home')->with('success', 'Package Created Successfully');
    }

    // Delete Package
    public function destroy(Package $package) {
        if($package->image) {
            Storage::disk('public')->delete($package->image);
        }
        $package->delete();
        return back()->with('success', 'Package Deleted');
    }
}