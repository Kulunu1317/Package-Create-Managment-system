<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Advertisement;

class HomeController extends Controller
{
    public function index() {
        $packages = Package::all();
        // Show only approved ads
        $ads = Advertisement::where('status', 'approved')->latest()->get();
        return view('welcome', compact('packages', 'ads'));
    }
}