<?php

namespace Modules\Ai\Services\Drivers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use NeuronAI\Chat\Messages\Message;

class GeminiAiDriver extends BaseDriver implements AiChatServiceInterface
{
    protected string $apiKey;
    protected string $apiEndpoint;
    protected string $defaultModel;
    protected string $defaultModelImages;
    protected bool $useCache;
    protected int $cacheDuration;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->apiKey = $config['api_key'] ?? env('GEMINI_API_KEY');
        $this->apiEndpoint = rtrim($config['api_endpoint'] ?? 'https://generativelanguage.googleapis.com/v1beta', '/');
        $this->defaultModel = $config['model'] ?? 'gemini-2.0-flash';
        $this->defaultModelImages = $config['model_images'] ?? 'gemini-2.0-flash-exp-image-generation';
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

        $model = $options['model'] ?? $this->defaultModel;
        $temperature = $options['temperature'] ?? 0.7;
        $maxTokens = $options['max_tokens'] ?? null;
        $schema = null;
        if (isset($options['schema']) and $options['schema']) {
            $schema = $options['schema'];
        }
        try {
            // Direct generateContent endpoint for API v1beta
            $endpoint = "/models/" . $model . ":generateContent";

            // Convert OpenAI format to Gemini API format
            $geminiPayload = [
                'contents' => $this->convertToGeminiFormat($messages),
                'generationConfig' => [
                    'temperature' => $temperature
                ]
            ];

            if ($maxTokens !== null) {
                $geminiPayload['generationConfig']['maxOutputTokens'] = $maxTokens;
            }


            $response = $this->makeRequest($endpoint, $geminiPayload);


            $result = [];

            // Process Gemini response
            if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
                $result = $response['candidates'][0]['content']['parts'][0]['text'];
            } elseif (isset($response['error'])) {
                throw new \Exception($response['error']['message'] ?? 'Unknown error');
            } else {
                // Fallback for other response formats
                $result = json_encode($response);
            }

            if ($schema) {

                $result = $this->parseJson($result);
            }

            if (!$result) {
                $result = [];
            }


            // Store in cache if caching is enabled
            if ($this->useCache) {
                Cache::put($cacheKey, $result, $this->cacheDuration * 60);
            }

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Convert OpenAI format messages to Gemini API format
     *
     * @param array $messages
     * @return array
     */
    protected function convertToGeminiFormat(array $messages): array
    {
        $contents = [];
        $currentUserContent = [];
        $currentRole = null;

        foreach ($messages as $message) {
            $role = $message['role'];
            $content = $message['content'];

            // If we have a role change, push the accumulated content
            if ($currentRole !== null && $currentRole !== $role) {
                $contents[] = [
                    'role' => $currentRole === 'user' ? 'user' : 'model',
                    'parts' => [
                        ['text' => implode("\n", $currentUserContent)]
                    ]
                ];
                $currentUserContent = [];
            }

            $currentRole = $role;

            if (is_array($content)) {
                // Handle multimodal content
                $parts = [];
                foreach ($content as $part) {
                    if ($part['type'] === 'text') {
                        $currentUserContent[] = $part['text'];
                    } elseif ($part['type'] === 'image_url' && isset($part['image_url']['url'])) {
                        $contents[] = [
                            'role' => $role === 'user' ? 'user' : 'model',
                            'parts' => [
                                [
                                    'text' => implode("\n", $currentUserContent)
                                ],
                                [
                                    'inline_data' => [
                                        'mime_type' => 'image/jpeg',
                                        'data' => str_replace('data:image/png;base64,', '', $part['image_url']['url'])
                                    ]
                                ]
                            ]
                        ];
                        $currentUserContent = [];
                    }
                }
            } else {
                $currentUserContent[] = $content;
            }
        }

        // Push any remaining content
        if (!empty($currentUserContent)) {
            $contents[] = [
                'role' => $currentRole === 'user' ? 'user' : 'model',
                'parts' => [
                    ['text' => implode("\n", $currentUserContent)]
                ]
            ];
        }

        return $contents;
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

        // Use vision model for image processing
        $model = $options['model'] ?? $this->defaultModelImages;

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ],
                        [
                            'inline_data' => [
                                'mime_type' => 'image/png',
                                'data' => $imageBase64
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseModalities' => ['Image']
            ]
        ];

        try {
            $endpoint = "/models/" . $model . ":generateContent";

            $response = $this->makeRequest($endpoint, $payload);

            if (isset($response['candidates'][0]['content']['parts'][0]['inlineData']['data'])) {
                return [
                    'data' => $response['candidates'][0]['content']['parts'][0]['inlineData']['data']
                ];
            } elseif (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
                return [
                    'text' => $response['candidates'][0]['content']['parts'][0]['text']
                ];
            }

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
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
        $url = $this->apiEndpoint . $endpoint . '?key=' . $this->apiKey;

        $headers = [
            'Content-Type: application/json'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            throw new \Exception("cURL Error: $error");
        }

        if ($httpCode >= 400) {
            throw new \Exception("Gemini API returned error code: $httpCode, Response: $result");
        }

        $decodedResult = json_decode($result, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Failed to decode JSON response: " . json_last_error_msg() . ", Raw response: $result");
        }

        return $decodedResult;
    }
}

