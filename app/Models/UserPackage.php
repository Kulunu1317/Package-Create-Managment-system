<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    protected $fillable = ['user_id', 'package_id', 'expires_at', 'ads_posted'];

    protected $casts = ['expires_at' => 'datetime'];

    public function package() {
        return $this->belongsTo(Package::class);
    }

    public function advertisements() {
        return $this->hasMany(Advertisement::class);
    }
    
    // Check if package is valid
    public function isValid() {
        return $this->expires_at->isFuture() && $this->ads_posted < $this->package->ad_limit;
    }
}