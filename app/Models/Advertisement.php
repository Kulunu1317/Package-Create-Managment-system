<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = ['user_id', 'user_package_id', 'job_name', 'job_type', 'company_logo', 'salary', 'description', 'status'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}