<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('landing');
Route::get('/en', LandingController::class)->name('landing.en');

Route::redirect('/admin/transactions', '/app/transactions');

Route::middleware('web')->group(function (): void {
    Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
        ->name('socialite.redirect');
    Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
        ->name('socialite.callback');
});
