<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', HomeController::class.'@index')->name('home');
});

Route::get('/lang/{locale}', LocaliztionController::class)->name('lang');

Auth::routes(['reset' => false, 'confirm' => false]);
