<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')->name('register');
    // Route::post('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

    Volt::route('login', 'pages.auth.login')->name('login');
    // Route::post('login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

    Volt::route('forgot-password', 'pages.auth.forgot-password')->name('password.request');
    // Route::post('forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')->name('password.reset');
    // Route::post('reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', App\Http\Controllers\Auth\LogoutController::class)->name('logout');

    Volt::route('verify-email', 'pages.auth.verify-email')->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    // Route::post('email/verification-notification', [App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
    //     ->middleware('throttle:6,1')
    //     ->name('verification.send');

    Volt::route('confirm-password', 'pages.auth.confirm-password')->name('password.confirm');
});
