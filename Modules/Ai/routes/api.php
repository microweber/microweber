<?php

use Illuminate\Support\Facades\Route;
use Modules\Ai\Facades\Ai;

Route::post('api/mcp', [Modules\Ai\Http\Controllers\McpController::class, 'handle'])
    ->middleware(['api', 'mcp.client'])
    ->name('api.ai.mcp');

Route::middleware(['admin'])->group(function () {
    Route::post('api/ai/chat', [Modules\Ai\Http\Controllers\AiController::class, 'chat'])
        ->name('api.ai.chat');

    Route::post('api/ai/generateImage', [Modules\Ai\Http\Controllers\AiController::class, 'generateImage'])
        ->name('api.ai.generateImage');

    // Agent chat with memory/history
    Route::post('api/ai/agent-chat', [Modules\Ai\Http\Controllers\AiController::class, 'agentChat'])
        ->name('api.ai.agent-chat');
    
    Route::get('api/ai/chat-history/{chatId}', [Modules\Ai\Http\Controllers\AiController::class, 'getChatHistory'])
        ->name('api.ai.chat-history');
    
    Route::get('api/ai/user-chats', [Modules\Ai\Http\Controllers\AiController::class, 'getUserChats'])
        ->name('api.ai.user-chats');
});
