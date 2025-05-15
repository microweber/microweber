<?php

use Illuminate\Support\Facades\Route;
use Modules\Ai\Facades\Ai;

Route::middleware(['admin'])->group(function () {
    Route::post('api/ai/chat', [Modules\Ai\Http\Controllers\AiController::class, 'chat'])
        ->name('api.ai.chat');

    Route::post('api/ai/generateImage', [Modules\Ai\Http\Controllers\AiController::class, 'generateImage'])
        ->name('api.ai.generateImage');
});
