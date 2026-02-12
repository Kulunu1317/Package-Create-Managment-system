<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    // Make sure 'validity_value' and 'duration_unit' are here!
    protected $fillable = [
        'name', 
        'price', 
        'image', 
        'description', 
        'ad_limit', 
        'validity_value', // The number (e.g. 30)
        'duration_unit'   // The unit (e.g. minutes)
    ];
}