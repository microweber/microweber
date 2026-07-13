<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Microweber Passport API Routes
|--------------------------------------------------------------------------
|
| Stateless token auth endpoints. The login route issues a Passport
| personal-access token; the logout route revokes the current one.
| These routes are framework-agnostic and work both inside the CMS
| and in a standalone Laravel application.
|
*/

Route::prefix('api/passport')
    ->middleware(['api'])
    ->name('mw-passport.')
    ->group(function () {

        Route::post('login', [\MicroweberPackages\Passport\Http\Controllers\Api\AuthController::class, 'login'])
            ->name('login')
            ->middleware(['throttle:60,1']);

        Route::post('logout', [\MicroweberPackages\Passport\Http\Controllers\Api\AuthController::class, 'logout'])
            ->name('logout')
            ->middleware(['auth:api']);
    });

/*
|--------------------------------------------------------------------------
| Authenticated User Route
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->get('/api/passport/user', function (Request $request) {
    return $request->user();
})->name('mw-passport.user');

/*
|--------------------------------------------------------------------------
| Token Audit Test Route (for testing the StampTokenLastUsed middleware)
|--------------------------------------------------------------------------
*/

if (app()->environment('testing')) {
    Route::middleware(['auth:api', 'token.audit'])->get('/api/passport/test-stamp', function () {
        return response()->json(['ok' => true]);
    })->name('mw-passport.test-stamp');
}