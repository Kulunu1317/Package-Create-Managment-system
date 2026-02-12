<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model {
    protected $fillable = [
        'user_id', 'user_package_id', 'job_name', 'job_type', 'company_logo', 
        'salary', 'description', 'status', 'expires_at', 
        'extension_requested_at', 'extension_value', 'extension_unit'
    ];
    protected $casts = ['expires_at' => 'datetime'];
    
    public function user() { return $this->belongsTo(User::class); }
}