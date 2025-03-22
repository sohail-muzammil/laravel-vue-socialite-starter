<?php

use App\Http\Controllers\Auth\UserSocialiteController;
use Illuminate\Support\Facades\Route;

Route::controller(UserSocialiteController::class)->middleware('web')->prefix('auth')->as('auth.socialite.')->group(function () {
    Route::get('redirect/{provider}', 'redirect')->name('redirect');
    Route::get('callback/{provider}',  'callback')->name('callback');
    Route::delete('disconnect/{driver}', 'disconnect')->name('disconnect');
});
