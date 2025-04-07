<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/test-manual-login', function() {
    $user = \MicroweberPackages\User\Models\User::where('email', 'admin@example.com')->first();
    
    if ($user) {
        Auth::login($user);
        return response()->json([
            'success' => true,
            'session_id' => session()->getId()
        ]);
    }

    return response()->json(['success' => false]);
});