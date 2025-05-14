<?php

namespace Modules\Ai\Services\Drivers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeminiAiDriver extends BaseDriver
{
    protected string $apiKey;
    protected string $apiEndpoint;
    protected string $defaultModel;
    protected bool $useCache;
    protected int $cacheDuration;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->apiKey = $config['api_key'] ?? env('GEMINI_API_KEY');
        $this->apiEndpoint = rtrim($config['api_endpoint'] ?? 'https://generativelanguage.googleapis.com/v1beta', '/');
        $this->defaultModel = $config['model'] ?? 'gemini-1.5-pro';
        $this->useCache = $config['use_cache'] ?? false;
        $this->cacheDuration = $config['cache_duration'] ?? 600;
    }

    /**
     * Get the name of this driver.
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'gemini';
    }

    /**
     * Send messages to chat and get a response.
     *
     * @param array $messages Array of messages
     * @param array $options Additional options
     * @return string|array The generated content or function call response
     */
    public function sendToChat(array $messages, array $options = []): string|array
    {
        // Check cache first if caching is enabled
        if ($this->useCache) {
            $cacheKey = 'gemini_' . md5(json_encode($messages) . json_encode($options));
            if ($cached = Cache::get($cacheKey)) {
                return $cached;
            }
        }

        // Convert OpenAI-style messages to Gemini format
        $geminiMessages = $this->convertToGeminiFormat($messages);

        $model = $options['model'] ?? $this->defaultModel;
        $temperature = $options['temperature'] ?? 0.7;
        $maxTokens = $options['max_tokens'] ?? null;

        $payload = [
            'contents' => $geminiMessages,
            'generationConfig' => [
                'temperature' => $temperature,
            ]
        ];

        if ($maxTokens !== null) {
            $payload['generationConfig']['maxOutputTokens'] = $maxTokens;
        }

        // Add JSON schema response format if provided
        if (isset($options['schema']) && $options['schema']) {
            $payload['generationConfig']['responseSchema'] = $options['schema'];
            $payload['generationConfig']['responseSchemaFollowingStrategy'] = 'STRICT';
        }

        try {
            $endpoint = "/models/{$model}:generateContent";
            $response = $this->makeRequest($endpoint, $payload);

            $result = null;

            // Extract content from Gemini response
            if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
                $result = $response['candidates'][0]['content']['parts'][0]['text'];
            } elseif (isset($response['promptFeedback']['blockReason'])) {
                // Handle content blocking
                $result = "Response blocked: " . ($response['promptFeedback']['blockReason'] ?? 'Unknown reason');
            } else {
                // Fallback for other response formats
                $result = json_encode($response);
            }

            // Store in cache if caching is enabled
            if ($this->useCache) {
                Cache::put($cacheKey, $result, $this->cacheDuration * 60);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Gemini API error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process an image with a text prompt.
     *
     * @param string $prompt The text prompt
     * @param string $imageBase64 Base64 encoded image data (with or without data URI prefix)
     * @param array $options Additional options
     * @return array Response from Gemini's image model
     */
    public function processImageWithPrompt(string $prompt, string $imageBase64, array $options = []): array
    {
        // Remove data URI prefix if present
        if (strpos($imageBase64, 'data:') === 0) {
            $parts = explode('base64,', $imageBase64, 2);
            if (count($parts) === 2) {
                $imageBase64 = $parts[1];
            }
        }

        $model = $options['model'] ?? 'gemini-2.0-flash-exp-image-generation';

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => 'image/png', // Adjust mime type if needed
                                'data' => $imageBase64
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseModalities' => ['Text', 'Image']
            ]
        ];

        try {
            $endpoint = "/models/{$model}:generateContent";
            return $this->makeRequest($endpoint, $payload);
        } catch (\Exception $e) {
            Log::error('Gemini image processing error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Convert OpenAI-style messages format to Gemini format.
     *
     * @param array $messages
     * @return array
     */
    protected function convertToGeminiFormat(array $messages): array
    {
        $geminiMessages = [];
        $currentMessage = [];
        $role = '';

        foreach ($messages as $message) {
            // Gemini uses "user" and "model" roles
            if ($message['role'] === 'system') {
                // Add system messages as user messages with a special prefix
                $currentMessage = [
                    'role' => 'user',
                    'parts' => [['text' => "[System Instruction] " . $message['content']]]
                ];
                $geminiMessages[] = $currentMessage;
            } elseif ($message['role'] === 'assistant') {
                $currentMessage = [
                    'role' => 'model',
                    'parts' => [['text' => $message['content']]]
                ];
                $geminiMessages[] = $currentMessage;
            } elseif ($message['role'] === 'user') {
                $currentMessage = [
                    'role' => 'user',
                    'parts' => [['text' => $message['content']]]
                ];
                $geminiMessages[] = $currentMessage;
            }
            // Note: Gemini doesn't have direct equivalent of "function" role
        }

        return $geminiMessages;
    }

    /**
     * Make an API request to the Gemini API.
     *
     * @param string $endpoint
     * @param array $data
     * @param string $method
     * @return array
     */
    protected function makeRequest(string $endpoint, array $data, string $method = 'POST'): array
    {
        $url = $this->apiEndpoint . $endpoint . "?key=" . urlencode($this->apiKey);

        $headers = [
            'Content-Type: application/json'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception("cURL Error: $error");
        }

        curl_close($ch);

        if ($httpCode >= 400) {
            throw new \Exception("Gemini API returned error code: $httpCode, Response: $result");
        }

        return json_decode($result, true);
    }
}
