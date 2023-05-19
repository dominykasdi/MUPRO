<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('registracija', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('registracija', [RegisteredUserController::class, 'store']);

    Route::get('prisijungimas', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('prisijungimas', [AuthenticatedSessionController::class, 'store']);

    Route::get('pamirstas-slaptazodis', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('pamirstas-slaptazodis', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('atstatymas/slaptazodis/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('atstatymas/slaptazodis', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('patvirtinimas/el-pastas', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('patvirtinimas/el-pastas/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('patvirtinimas/el-pastas/patvirtinimo-pranesimas', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('patvirtinimas/slaptazodis', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('patvirtinimas/slaptazodis', [ConfirmablePasswordController::class, 'store']);

    Route::put('slaptazodis', [PasswordController::class, 'update'])->name('password.update');

    Route::post('atsijungimas', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
