<?php

use Illuminate\Support\Facades\Route;
use Modules\Ai\Facades\Ai;

Route::post('api/mcp', [Modules\Ai\Http\Controllers\McpController::class, 'handle'])
    ->middleware(['api', 'mcp.client'])
    ->name('api.ai.mcp');

// Streamable HTTP transport-level verbs (MCP 2025-03-26 / 2025-06-18).
// GET is the optional server→client SSE channel — this tools-only server
// returns a clean 405 (no server-initiated messages); DELETE terminates a
// session. Neither carries a JSON-RPC body, so they skip the `mcp.client`
// tool-auth middleware (which parses the body to resolve a tool + scope)
// and act as transport no-ops that keep spec-strict remote clients
// (OpenCode, mcp-remote, Open WebUI) from erroring on their handshake
// probes. Declared before Microweber's catch-all page router so GET
// /api/mcp resolves here instead of 403-ing as an unknown page.
Route::get('api/mcp', [Modules\Ai\Http\Controllers\McpController::class, 'stream'])
    ->middleware(['api'])
    ->name('api.ai.mcp.stream');

Route::delete('api/mcp', [Modules\Ai\Http\Controllers\McpController::class, 'terminate'])
    ->middleware(['api'])
    ->name('api.ai.mcp.terminate');

// Legacy HTTP+SSE transport (MCP 2024-11-05) for clients that don't speak
// Streamable HTTP. GET /api/mcp/sse opens the event stream (and hands back
// the messages endpoint); POST /api/mcp/messages?sessionId=… carries the
// client's JSON-RPC requests, whose responses are relayed over the stream.
// Both authenticate with the same `mcp.client` middleware as POST /api/mcp
// (GET /sse has no body, so only the base scope is checked). Declared before
// Microweber's catch-all page router so these resolve instead of 403-ing.
Route::get('api/mcp/sse', [Modules\Ai\Http\Controllers\McpSseController::class, 'sse'])
    ->middleware(['api', 'mcp.client'])
    ->name('api.ai.mcp.sse');

Route::post('api/mcp/messages', [Modules\Ai\Http\Controllers\McpSseController::class, 'messages'])
    ->middleware(['api', 'mcp.client'])
    ->name('api.ai.mcp.messages');

Route::middleware(['admin'])->group(function () {
    Route::post('api/ai/chat', [Modules\Ai\Http\Controllers\AiController::class, 'chat'])
        ->name('api.ai.chat');

    // task-2026-09-29-rewrite — one-shot text rewrite for the Live Edit text
    // toolbar's ✦ Rewrite (replaces text inline instead of opening the AI panel).
    Route::post('api/ai/rewrite-text', [Modules\Ai\Http\Controllers\AiController::class, 'rewriteText'])
        ->name('api.ai.rewrite-text');

    Route::post('api/ai/generateImage', [Modules\Ai\Http\Controllers\AiController::class, 'generateImage'])
        ->name('api.ai.generateImage');

    // Agent chat with memory/history
    Route::post('api/ai/agent-chat', [Modules\Ai\Http\Controllers\AiController::class, 'agentChat'])
        ->name('api.ai.agent-chat');

    // Streaming Live-Edit chat (Server-Sent Events): tool calls stream to the
    // browser as they happen so frontend tools apply edits on the live canvas.
    Route::post('api/ai/agent-chat-stream', [Modules\Ai\Http\Controllers\AiController::class, 'agentChatStream'])
        ->name('api.ai.agent-chat-stream');
    
    Route::get('api/ai/chat-history/{chatId}', [Modules\Ai\Http\Controllers\AiController::class, 'getChatHistory'])
        ->name('api.ai.chat-history');
    
    Route::get('api/ai/user-chats', [Modules\Ai\Http\Controllers\AiController::class, 'getUserChats'])
        ->name('api.ai.user-chats');
});
