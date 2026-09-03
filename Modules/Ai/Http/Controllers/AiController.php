<?php

namespace Modules\Ai\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Ai\Facades\Ai;
use Modules\Ai\Facades\AiImages;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Models\AgentChatMessage;
use Modules\Ai\Services\AgentFactory;
use NeuronAI\Chat\Messages\UserMessage;

class AiController extends Controller
{
    public function generateImage(Request $request)
    {
        $rules = [
            'messages' => 'required|array',
            'options' => 'sometimes|array',

        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->toArray()
            ], 422);
        }


        $request->validate($rules);


        if (!$request->input('messages')) {
            return response()->json([
                'success' => false,
                'message' => 'Messages are required'
            ], 422);
        }

        $messages = $request->input('messages');
         $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->toArray()
            ], 422);
        }

         $imageUrl = $request->input('url');
        $options = $request->input('options', []);


        if ($imageUrl) {
            $options['image'] = $imageUrl;
        }

        try {
            // Process the image with AI
            $response = AiImages::generateImage($messages, $options);

            $result = [
                'success' => true,
                'data' => $response,

            ];


            // Add the URL to the frontend response if available
            if (isset($response['url'])) {
                $result['url'] = $response['url'];
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function chat(Request $request)
    {
        $rules = [
            'messages' => 'required|array',
            'messages.*.role' => 'sometimes|string|in:system,user,assistant,function',
            'messages.*.content' => 'sometimes|string',
            'messages.*.name' => 'sometimes|string',
            'options' => 'sometimes|array',
            'options.functions' => 'sometimes|array',
            'options.function_call' => 'sometimes|string',
            'options.model' => 'sometimes|string',
            'options.temperature' => 'sometimes|numeric',
            'options.max_tokens' => 'sometimes|integer',
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->toArray()
            ], 422);
        }


        $request->validate($rules);


        if (!$request->input('messages')) {
            return response()->json([
                'success' => false,
                'message' => 'Messages are required'
            ], 422);
        }

        $messages = $request->input('messages');
        $options = $request->input('options', []);

        $response = Ai::sendToChat($messages, $options);



        if (is_string($response)) {
            $response = @json_decode($response);
        }

        try {

            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Chat with AI agent using persistent memory/history
     */
    public function agentChat(Request $request)
    {
        $rules = [
            'message' => 'required|string|max:2000',
            'agent_type' => 'required|string|in:general,content,customer,shop,media,liveedit',
            'chat_id' => 'sometimes|integer|exists:agent_chats,id',
            'chat_title' => 'sometimes|string|max:255',
            'content_id' => 'sometimes|integer',
            'options' => 'sometimes|array',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->toArray()
            ], 422);
        }

        try {
            $agentFactory = app(AgentFactory::class);
            $userId = auth()->id();
            $message = $request->input('message');
            $agentType = $request->input('agent_type');
            $chatId = $request->input('chat_id');
            $chatTitle = $request->input('chat_title', 'AI Chat - ' . now()->format('M j, H:i'));

            // Get or create chat
            if ($chatId) {
                $chat = AgentChat::findOrFail($chatId);
                
                // Verify user has access to this chat
                if ($chat->user_id && $chat->user_id !== $userId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access to chat'
                    ], 403);
                }
            } else {
                $chat = $agentFactory->createOrGetChat(
                    agentType: $agentType,
                    title: $chatTitle,
                    userId: $userId
                );
            }

            // Create agent with chat history
            $agent = $agentFactory->agentWithChat($chat);

            // Collect the tool calls the agent makes this turn. The Live-Edit
            // tools are side-effect-free command emitters; the canvas applies the
            // collected { tool, args } to the real DOM and marks them dirty for the
            // normal Live-Edit SAVE.
            $toolCallCollector = new \Modules\Ai\Services\ToolCallCollector();
            $agent->observe($toolCallCollector);

            // Save user message to database
            $userMessage = AgentChatMessage::create([
                'chat_id' => $chat->id,
                'role' => 'user',
                'content' => $message,
                'metadata' => [
                    'user_id' => $userId,
                    'timestamp' => now()->toISOString(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);

            // Process with agent (which will use chat history). For Live-Edit,
            // prepend the page context so the model knows which page it is editing
            // and can pass content_id to tools that need it.
            $contentId = (int) $request->input('content_id', 0);
            $promptText = $message;
            if ($agentType === 'liveedit' && $contentId > 0) {
                $promptText = "[Live-Edit context: you are editing the page with content_id={$contentId}. Pass this content_id to any tool that needs it.]\n\n" . $message;
            }
            $neuronMessage = new UserMessage($promptText);
            $response = $agent->chat($neuronMessage)->getMessage();

            // Extract response content
            $responseContent = '';
            if ($response instanceof \NeuronAI\Chat\Messages\Message) {
                $responseContent = $response->getContent();
            } elseif (is_string($response)) {
                $responseContent = $response;
            } else {
                $responseContent = 'I processed your message but couldn\'t generate a proper response.';
            }

            // Save AI response to database
            $assistantMessage = AgentChatMessage::create([
                'chat_id' => $chat->id,
                'role' => 'assistant',
                'content' => $responseContent,
                'agent_type' => $agentType,
                'metadata' => [
                    'processed_by' => $agentType,
                    'timestamp' => now()->toISOString(),
                    'tools_used' => count($agent->getTools()),
                ],
                'processed_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'response' => $responseContent,
                    'chat_id' => $chat->id,
                    'message_id' => $assistantMessage->id,
                    'agent_type' => $agentType,
                    'chat_title' => $chat->title,
                    'message_count' => $chat->getMessageCount(),
                    // Ordered tool calls this turn: [{tool, args}]. The Live-Edit
                    // canvas applies these to the real DOM (apply_css, etc.).
                    'edits' => $toolCallCollector->all(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing agent chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Streaming Live-Edit chat over Server-Sent Events.
     *
     * The Live-Edit tools are frontend tools: the model calls them, and this
     * endpoint streams each call to the browser as an `event: tool` frame the
     * instant it happens, so the canvas applies the edit live (see mw-ai.js
     * frontendTools). The whole page canvas is passed up as context so the model
     * writes correct selectors. Backend tools/agents are unchanged.
     */
    public function agentChatStream(Request $request)
    {
        $rules = [
            'message' => 'required|string|max:4000',
            'agent_type' => 'sometimes|string|in:general,content,customer,shop,media,liveedit',
            'chat_id' => 'sometimes|integer|exists:agent_chats,id',
            'chat_title' => 'sometimes|string|max:255',
            'content_id' => 'sometimes|integer',
            'canvas_html' => 'sometimes|string',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->toArray(),
            ], 422);
        }

        $userId = auth()->id();
        $message = $request->input('message');
        $agentType = $request->input('agent_type', 'liveedit');
        $chatId = $request->input('chat_id');
        $chatTitle = $request->input('chat_title', 'Live Edit - ' . now()->format('M j, H:i'));
        $contentId = (int) $request->input('content_id', 0);
        $canvasHtml = (string) $request->input('canvas_html', '');

        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use (
            $userId, $message, $agentType, $chatId, $chatTitle, $contentId, $canvasHtml, $request
        ) {
            $emitter = new \Modules\Ai\Services\SseToolEmitter();

            try {
                $agentFactory = app(AgentFactory::class);

                if ($chatId) {
                    $chat = AgentChat::findOrFail($chatId);
                    if ($chat->user_id && $chat->user_id !== $userId) {
                        $emitter->emit('error', ['message' => 'Unauthorized access to chat']);
                        return;
                    }
                } else {
                    $chat = $agentFactory->createOrGetChat(
                        agentType: $agentType,
                        title: $chatTitle,
                        userId: $userId
                    );
                }

                $emitter->emit('start', ['chat_id' => $chat->id, 'agent_type' => $agentType]);

                $agent = $agentFactory->agentWithChat($chat);
                $agent->observe($emitter);

                AgentChatMessage::create([
                    'chat_id' => $chat->id,
                    'role' => 'user',
                    'content' => $message,
                    'metadata' => [
                        'user_id' => $userId,
                        'timestamp' => now()->toISOString(),
                        'ip_address' => $request->ip(),
                    ],
                ]);

                // Build the prompt: Live-Edit context + a cleaned excerpt of the
                // real page canvas so the model targets existing selectors.
                $promptText = $message;
                if ($agentType === 'liveedit') {
                    $preamble = '[Live-Edit session';
                    if ($contentId > 0) {
                        $preamble .= " editing content_id={$contentId}";
                    }
                    $preamble .= '. Apply visual/content changes by calling your tools; they run live on the canvas.]';

                    $canvasContext = $this->summarizeCanvas($canvasHtml);
                    if ($canvasContext !== '') {
                        $preamble .= "\n\n[Current page canvas markup]\n" . $canvasContext;
                    }
                    $promptText = $preamble . "\n\n" . $message;
                }

                $neuronMessage = new UserMessage($promptText);
                $result = $agent->chat($neuronMessage)->getMessage();

                $responseContent = '';
                if ($result instanceof \NeuronAI\Chat\Messages\Message) {
                    $responseContent = (string) $result->getContent();
                } elseif (is_string($result)) {
                    $responseContent = $result;
                }
                if ($responseContent === '') {
                    $responseContent = 'Done.';
                }

                $assistantMessage = AgentChatMessage::create([
                    'chat_id' => $chat->id,
                    'role' => 'assistant',
                    'content' => $responseContent,
                    'agent_type' => $agentType,
                    'metadata' => [
                        'processed_by' => $agentType,
                        'timestamp' => now()->toISOString(),
                        'edits' => count($emitter->all()),
                    ],
                    'processed_at' => now(),
                ]);

                $emitter->emit('done', [
                    'response' => $responseContent,
                    'chat_id' => $chat->id,
                    'message_id' => $assistantMessage->id,
                    'agent_type' => $agentType,
                    'chat_title' => $chat->title,
                    // Repeat the ordered tool calls so clients that batch-apply at
                    // the end (rather than live) get the full list.
                    'edits' => $emitter->all(),
                ]);
            } catch (\Throwable $e) {
                $emitter->emit('error', ['message' => 'Error: ' . $e->getMessage()]);
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }

    /**
     * Reduce raw canvas HTML to a compact, model-friendly excerpt: strip
     * script/style/svg/comments, collapse whitespace, cap length. Keeps enough
     * structure (tags, classes, ids, text) for the model to write selectors.
     */
    protected function summarizeCanvas(string $html): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }

        $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html) ?? $html;
        $html = preg_replace('#<style\b[^>]*>.*?</style>#is', '', $html) ?? $html;
        $html = preg_replace('#<svg\b[^>]*>.*?</svg>#is', '', $html) ?? $html;
        $html = preg_replace('#<!--.*?-->#s', '', $html) ?? $html;
        $html = preg_replace('/\s+/', ' ', $html) ?? $html;
        $html = trim($html);

        $max = 9000;
        if (mb_strlen($html) > $max) {
            $html = mb_substr($html, 0, $max) . ' …[truncated]';
        }

        return $html;
    }

    /**
     * Get chat history for a specific chat
     */
    public function getChatHistory(Request $request, int $chatId)
    {
        try {
            $chat = AgentChat::findOrFail($chatId);
            $userId = auth()->id();

            // Verify user has access to this chat
            if ($chat->user_id && $chat->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to chat'
                ], 403);
            }

            $messages = $chat->messages()
                ->orderBy('created_at')
                ->paginate($request->input('per_page', 50));

            return response()->json([
                'success' => true,
                'data' => [
                    'chat' => $chat,
                    'messages' => $messages,
                    'stats' => [
                        'total_messages' => $chat->getMessageCount(),
                        'user_messages' => $chat->getUserMessageCount(),
                        'assistant_messages' => $chat->getAssistantMessageCount(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving chat history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List user's chats
     */
    public function getUserChats(Request $request)
    {
        try {
            $userId = auth()->id();
            
            $chats = AgentChat::where('user_id', $userId)
                ->orWhereNull('user_id')
                ->with(['messages' => function($query) {
                    $query->latest()->limit(1);
                }])
                ->orderBy('updated_at', 'desc')
                ->paginate($request->input('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => $chats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving chats: ' . $e->getMessage()
            ], 500);
        }
    }
}

