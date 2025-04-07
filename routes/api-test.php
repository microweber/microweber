<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::post('/test-login', function() {
    $credentials = request()->only('email', 'password');
    
    if (Auth::attempt($credentials)) {
        return response()->json([
            'success' => true,
            'user' => Auth::user()
        ]);
    }

    return response()->json(['success' => false], 401);
});