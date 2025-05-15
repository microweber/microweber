<?php

use Illuminate\Support\Facades\Route;
use Modules\Ai\Facades\Ai;

Route::middleware(['admin'])->group(function () {
    Route::post('api/ai/chat', [Modules\Ai\Http\Controllers\AiController::class, 'chat'])
        ->name('api.ai.chat');

    Route::post('api/ai/edit-image', [Modules\Ai\Http\Controllers\AiController::class, 'editImage'])
        ->name('api.ai.edit-image');
});
