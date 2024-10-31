<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SocialController;

Route::get('/', [HomeController::class,'index'])->name('frontend.home');
/*
    Route::name('frontend.')->middleware(['auth', 'isUser'])->group(function () {
        Route::get('/dashboard', [HomeController::class,'dashboard']);
        Route::get('/profile', [HomeController::class,'profile'])->name('profile');
        Route::post('/profile', [HomeController::class,'profileUpdate'])->name('profileUpdate');
        Route::post('/profile-password', [HomeController::class,'profilePasswordUpdate'])->name('profilePasswordUpdate');
        Route::post('/profile-image', [HomeController::class,'profileImageUpdate'])->name('profileImageUpdate');
        Route::get('/my-favorite', [HomeController::class,'myFavorite'])->name('myFavorite');
        Route::post('/logout', [HomeController::class,'logout']);
    });
*/

// Route::any('/test', [HomeController::class,'test']);
Route::get('auth/google', [SocialController::class, 'redirectToGoogle'])->name('googleLogin');
Route::get('auth/google/callback', [SocialController::class, 'handleGoogleCallback']);

Route::get('auth/facebook', [SocialController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [SocialController::class, 'handleFacebookCallback']);
