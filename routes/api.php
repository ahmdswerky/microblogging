<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Website')->group(function () {
    Route::post('login', AuthenticationController::class."@login");
    Route::post('register', AuthenticationController::class."@register");
});

Route::namespace('Website')->middleware('auth:api')->group(function () {
    Route::get('user', UserProfileController::class);
    Route::delete('logout', AuthenticationController::class."@logout");

    Route::apiResource('tweets', TweetController::class)->middleware('account.twitter')->except('update');
    Route::post('social/{provider}/follow/{username}', FollowController::class."@store")->middleware('account.twitter');
});
