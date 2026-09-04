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
            'screenshot' => 'sometimes|string',
            'reference_images' => 'sometimes|array|max:4',
            'reference_images.*' => 'string',
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
        // Title new sessions by their first message so they are recognizable in
        // the resume list; fall back to a timestamp for empty/image-only turns.
        $chatTitle = $request->input('chat_title')
            ?: (trim((string) $message) !== ''
                ? \Illuminate\Support\Str::limit(trim((string) $message), 48)
                : 'Live Edit - ' . now()->format('M j, H:i'));
        $contentId = (int) $request->input('content_id', 0);
        $canvasHtml = (string) $request->input('canvas_html', '');
        $screenshot = (string) $request->input('screenshot', '');
        $referenceImages = (array) $request->input('reference_images', []);
        $lastModule = (array) $request->input('last_module', []);
        $editFields = (array) $request->input('edit_fields', []);

        // Bind the live canvas context for this request so the read tools
        // (get_dom, get_edit_fields) can return the real current page state.
        app()->instance('mw.ai.liveedit.context', [
            'dom' => $canvasHtml,
            'edit_fields' => $editFields,
        ]);

        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use (
            $userId, $message, $agentType, $chatId, $chatTitle, $contentId, $canvasHtml, $screenshot, $referenceImages, $lastModule, $request
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

                    // Tell the model which module it just inserted, so it can target
                    // it with get_module_settings / set_module_option / add_form_field
                    // (the SSE stream is one-way — the model can't read the DOM back).
                    $lmId = trim((string) ($lastModule['id'] ?? ''));
                    if ($lmId !== '') {
                        $lmType = trim((string) ($lastModule['type'] ?? ''));
                        $preamble .= "\n\n[Last inserted module: id={$lmId}"
                            . ($lmType !== '' ? " type={$lmType}" : '')
                            . '. Use this module_id when calling get_module_settings, set_module_option or add_form_field.]';
                    }

                    $canvasContext = $this->summarizeCanvas($canvasHtml);
                    if ($canvasContext !== '') {
                        $preamble .= "\n\n[Current page canvas markup]\n" . $canvasContext;
                    }

                    // Reference design: the user pasted/attached screenshot(s) of a
                    // design to recreate. Read them with the vision model into a
                    // concrete build spec for the (text-only) editing model.
                    if (!empty($referenceImages)) {
                        $spec = $this->describeReference($referenceImages, $message);
                        if ($spec !== '') {
                            $emitter->emit('reference', ['spec' => $spec]);
                            $preamble .= "\n\n[REFERENCE DESIGN TO RECREATE — the user pasted a screenshot of the design they want. "
                                . "Rebuild it on the page section by section with add_section (+css) to match this as closely as possible:\n"
                                . $spec . "]";
                        }
                    }

                    // Vision: let the (text-only) editing model "see" the page by
                    // describing a screenshot of the live canvas with a vision model.
                    if ($screenshot !== '') {
                        $visual = $this->describeCanvas($screenshot, $message);
                        if ($visual !== '') {
                            $emitter->emit('vision', ['description' => $visual]);
                            $preamble .= "\n\n[What the page looks like right now, from a screenshot: " . $visual . "]";
                        }
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
     * Describe a screenshot of the live canvas with a vision model so the
     * text-only editing model can "see" the current design.
     *
     * Kimi (the tool-caller) has no vision, so we route the base64 screenshot
     * to a local vision model (gemma3:4b by default) and feed its short
     * description back into the prompt. Best-effort: any failure/timeout returns
     * '' and the turn proceeds on markup context alone.
     */
    protected function describeCanvas(string $screenshot, string $userRequest): string
    {
        $b64 = $screenshot;
        if (str_contains($b64, ',')) {
            // Strip the "data:image/jpeg;base64," prefix.
            $b64 = substr($b64, strpos($b64, ',') + 1);
        }
        if (strlen($b64) < 100) {
            return '';
        }

        $base = rtrim((string) (config('modules.ai.drivers.ollama.url') ?: env('OLLAMA_API_URL', 'http://localhost:11434/api')), '/');
        // Default to a cloud vision model — it is GPU-backed and fast (~1s),
        // whereas a local vision model on a CPU-only host takes minutes and would
        // always hit the timeout below. Override with AI_VISION_MODEL.
        $model = (string) (config('modules.ai.vision_model') ?: env('AI_VISION_MODEL', 'gemma4:cloud'));

        $prompt = "You are looking at a screenshot of a web page that is being edited in a website builder. "
            . "In 3-5 sentences, describe its current visual design: overall layout and sections top-to-bottom, "
            . "the color scheme, typography and spacing, and any obvious visual problems (misalignment, clashing "
            . "colors, unstyled or broken areas). Be concrete. This will help another AI make edits for the "
            . "request: \"" . mb_substr($userRequest, 0, 300) . "\".";

        try {
            $res = \Illuminate\Support\Facades\Http::timeout(25)->post($base . '/generate', [
                'model' => $model,
                'prompt' => $prompt,
                'images' => [$b64],
                'stream' => false,
                'options' => ['temperature' => 0.2],
            ]);
            if ($res->successful()) {
                $text = trim((string) ($res->json('response') ?? ''));
                return mb_substr($text, 0, 1200);
            }
        } catch (\Throwable $e) {
            // best-effort only
        }
        return '';
    }

    /**
     * Read one or more reference screenshots (a design the user pasted to
     * recreate) with the vision model and return a concrete, section-by-section
     * build spec the text-only editing model can follow. Best-effort.
     */
    protected function describeReference(array $images, string $userRequest): string
    {
        $b64s = [];
        foreach ($images as $img) {
            $s = (string) $img;
            if (str_contains($s, ',')) {
                $s = substr($s, strpos($s, ',') + 1);
            }
            if (strlen($s) > 100) {
                $b64s[] = $s;
            }
        }
        if (empty($b64s)) {
            return '';
        }

        $base = rtrim((string) (config('modules.ai.drivers.ollama.url') ?: env('OLLAMA_API_URL', 'http://localhost:11434/api')), '/');
        $model = (string) (config('modules.ai.vision_model') ?: env('AI_VISION_MODEL', 'gemma4:cloud'));

        $prompt = "This is a screenshot of a website design the user wants to recreate. "
            . "Produce a precise build spec another AI can follow to rebuild it. List every section "
            . "from top to bottom; for each section give: the heading text and any eyebrow/label, the "
            . "body/subtitle text, any cards or columns (title + short description), button/link labels, "
            . "the background color (hex), the text color, and the layout (centered / grid-of-N / two-column). "
            . "Then give the overall color palette as hex values and the font style (e.g. modern sans-serif). "
            . "Be concrete and exhaustive; use short lines.";
        if (trim($userRequest) !== '') {
            $prompt .= " User note: \"" . mb_substr($userRequest, 0, 300) . "\".";
        }

        try {
            $res = \Illuminate\Support\Facades\Http::timeout(40)->post($base . '/generate', [
                'model' => $model,
                'prompt' => $prompt,
                'images' => $b64s,
                'stream' => false,
                'options' => ['temperature' => 0.2, 'num_predict' => 900],
            ]);
            if ($res->successful()) {
                return mb_substr(trim((string) ($res->json('response') ?? '')), 0, 4000);
            }
        } catch (\Throwable $e) {
            // best-effort only
        }
        return '';
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
                ->withCount('messages')
                ->orderBy('updated_at', 'desc')
                ->paginate($request->input('per_page', 30));

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

