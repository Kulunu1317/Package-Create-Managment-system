<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Advertisement;

class HomeController extends Controller
{
  public function index() {
        $packages = Package::all();
        
        // SORTING LOGIC: 
        // We order by FIELD: Diamond=4, Gold=3, Silver=2, Normal=1. DESC puts 4 first.
        $ads = Advertisement::where('status', 'approved')
            ->orderByRaw("FIELD(tier, 'normal', 'silver', 'gold', 'diamond') DESC") 
            ->orderBy('created_at', 'desc') // If tiers are equal, show newest first
            ->get();

        return view('welcome', compact('packages', 'ads'));
    }
}