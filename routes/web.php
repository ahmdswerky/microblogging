<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Auth::routes(['reset' => false, 'confirm' => false]);

Route::get('/', HomeController::class.'@index')->middleware('auth')->name('home');

// Localization
Route::get('/lang/{locale}', LocaliztionController::class)->name('lang');

// Socialite Routes
Route::namespace('Website')->group(function () {
    Route::get('auth/{provider}', AuthenticationController::class.'@redirectToProvider')->name('auth.social');
    Route::get('auth/{provider}/callback', AuthenticationController::class.'@handleProviderCallback');
});

