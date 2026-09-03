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

