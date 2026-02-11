<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'telephone', 'birthday', 'profile_photo', 'role'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array {
        return ['password' => 'hashed', 'birthday' => 'date'];
    }

    public function purchasedPackages() {
        return $this->hasMany(UserPackage::class);
    }
}