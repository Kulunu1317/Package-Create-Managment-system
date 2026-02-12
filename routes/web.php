<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\User\UserPackageController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\Admin\ApprovalController;

// Public & Auth
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

    // ADMIN ROUTES
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('packages', PackageController::class);
        
        // Ads
        Route::get('/ads', [AdvertisementController::class, 'adminIndex'])->name('ads.index');
        Route::patch('/ads/{id}/approve', [AdvertisementController::class, 'approve'])->name('ads.approve');
        Route::patch('/ads/{id}/reject', [AdvertisementController::class, 'reject'])->name('ads.reject');

        // Notification Panel
        Route::get('/notifications', [ApprovalController::class, 'index'])->name('notifications');
        Route::post('/renew/{id}/approve', [ApprovalController::class, 'approveRenewal'])->name('approve_renewal');
        Route::post('/extend/{id}/approve', [ApprovalController::class, 'approveExtension'])->name('approve_extension');
        
        // *** THIS IS THE CRITICAL REJECT ROUTE ***
        Route::post('/extend/{id}/reject', [ApprovalController::class, 'rejectExtension'])->name('reject_extension');
    });

    // USER ROUTES
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        Route::post('/buy-package/{package}', [UserPackageController::class, 'buy'])->name('buy_package');
        Route::get('/my-packages', [UserPackageController::class, 'index'])->name('my_packages');
        Route::post('/package/{id}/renew', [UserPackageController::class, 'requestRenewal'])->name('renew_package');
        Route::get('/create-ad/{userPackage}', [AdvertisementController::class, 'create'])->name('create_ad');
        Route::post('/store-ad', [AdvertisementController::class, 'store'])->name('store_ad');
        Route::post('/ad/{id}/extend', [AdvertisementController::class, 'updateTime'])->name('extend_ad');
        Route::get('/notifications', function() {
            return view('user.notifications', ['notifications' => \App\Models\Notification::where('user_id', \Illuminate\Support\Facades\Auth::id())->latest()->get()]);
        })->name('notifications');
    });
});