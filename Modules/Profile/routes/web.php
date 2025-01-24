<?php

use Illuminate\Support\Facades\Route;
use Modules\Profile\Filament\Pages\TwoFactorAuth;

Route::middleware(['web', 'auth'])->group(function() {
    // Two-Factor Authentication Routes
    Route::prefix('2fa')->middleware('2fa.rate_limit')->group(function() {
        Route::get('/enable', [TwoFactorAuth::class, 'enableTwoFactorAuthentication'])
            ->name('profile.2fa.enable');
            
        Route::post('/confirm', [TwoFactorAuth::class, 'confirmTwoFactorAuthentication'])
            ->name('profile.2fa.confirm');
            
        Route::post('/disable', [TwoFactorAuth::class, 'disableTwoFactorAuthentication'])
            ->name('profile.2fa.disable');
            
        Route::get('/recovery-codes', [TwoFactorAuth::class, 'showRecoveryCodes'])
            ->name('profile.2fa.recovery-codes');
            
        Route::post('/regenerate-recovery-codes', [TwoFactorAuth::class, 'regenerateRecoveryCodes'])
            ->name('profile.2fa.regenerate-recovery-codes');
    });
});
