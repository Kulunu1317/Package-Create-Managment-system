<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\User\UserPackageController;
use App\Http\Controllers\AdvertisementController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    
    // Profile
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('packages', PackageController::class);
        Route::get('/ads', [AdvertisementController::class, 'adminIndex'])->name('ads.index');
        Route::patch('/ads/{id}/approve', [AdvertisementController::class, 'approve'])->name('ads.approve');
        Route::patch('/ads/{id}/reject', [AdvertisementController::class, 'reject'])->name('ads.reject');
    });

    // User Routes
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        Route::post('/buy-package/{package}', [UserPackageController::class, 'buy'])->name('buy_package');
        Route::get('/my-packages', [UserPackageController::class, 'index'])->name('my_packages');
        Route::get('/create-ad/{userPackage}', [AdvertisementController::class, 'create'])->name('create_ad');
        Route::post('/store-ad', [AdvertisementController::class, 'store'])->name('store_ad');
    });
});