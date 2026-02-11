<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    // 1. Show all packages (Admin View)
    public function index() {
        return view('welcome', ['packages' => Package::all()]);
    }

    // 2. Show the "Create Package" Form (THIS WAS MISSING)
    public function create() {
        return view('admin.packages.create');
    }

    // 3. Store the new package in database
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required',
            'ad_limit' => 'required|integer',
            'validity_days' => 'required|integer'
        ]);

        $path = $request->file('image')->store('packages', 'public');
        
        Package::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $path,
            'description' => $request->description,
            'ad_limit' => $request->ad_limit,
            'validity_days' => $request->validity_days,
        ]);

        return redirect()->route('home')->with('success', 'Package Created');
    }

    // 4. Delete a package
    public function destroy(Package $package) {
        if($package->image) {
            Storage::disk('public')->delete($package->image);
        }
        $package->delete();
        return back()->with('success', 'Package Deleted');
    }
}