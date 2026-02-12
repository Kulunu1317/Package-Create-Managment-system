<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserPackage extends Model
{
    protected $fillable = [
    'user_id', 'package_id', 'expires_at', 'status', 'tier', 'renewal_requested_at' // Add 'tier'
];

    protected $casts = [
        'expires_at' => 'datetime',
        'renewal_requested_at' => 'datetime',
    ];

    public function package() {
        return $this->belongsTo(Package::class);
    }

    public function advertisements() {
        return $this->hasMany(Advertisement::class);
    }
    
    // --- THIS WAS MISSING ---
    // It checks if the package is active, not expired, and has ad slots left.
    public function isValid() {
        return $this->status === 'active' 
            && $this->expires_at->isFuture() 
            && $this->ads_posted < $this->package->ad_limit;
    }
}