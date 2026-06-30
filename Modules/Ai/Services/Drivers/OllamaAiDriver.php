<?php

namespace Modules\Ai\Services\Drivers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use MicroweberPackages\Http\HttpClientFactory;

class OllamaAiDriver extends BaseDriver
{
    /**
     * The Ollama API URL
     *
     * @var string
     */
    protected string $apiUrl;

    /**
     * The default model to use
     *
     * @var string
     */
    protected string $defaultModel;

    /**
     * Optional API key for remote Ollama instances
     *
     * @var string|null
     */
    protected ?string $apiKey;

    /**
     * Whether to use caching
     *
     * @var bool
     */
    protected bool $useCache;

    /**
     * Cache duration in minutes
     *
     * @var int
     */
    protected int $cacheDuration;

    /**
     * Create a new Ollama driver instance.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $url = $config['url'] ?? env('OLLAMA_API_URL', 'http://localhost:11434/api/generate');
        // Normalize URL: if user only provided the base URL, append the generate endpoint
        $url = rtrim($url, '/');
        if (preg_match('#:\d+$#', $url)) {
            $url .= '/api/generate';
        } elseif (str_ends_with($url, '/api')) {
            $url .= '/generate';
        }
        $this->apiUrl = $url;

        $this->defaultModel = $config['model'] ?? env('OLLAMA_MODEL', 'llama3.1');
        $this->apiKey = $config['api_key'] ?? env('OLLAMA_API_KEY');
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
        return 'ollama';
    }

    /**
     * Send messages to chat and get a response.
     *
     * @param array $messages Array of messages
     * @param array $options Additional options
     * @return string|array The generated content
     */
    public function sendToChat(array $messages, array $options = []): string|array
    {
        // Check cache first if caching is enabled
        if ($this->useCache) {
            $cacheKey = 'ollama_' . md5(json_encode($messages) . json_encode($options));
            if ($cached = Cache::get($cacheKey)) {
                return $cached;
            }
        }

        // Convert messages to a format Ollama can understand
        $prompt = $this->formatMessagesForOllama($messages);

        $model = $options['model'] ?? $this->defaultModel;
        $temperature = $options['temperature'] ?? 0.7;
        $schema = null;
        if (isset($options['schema']) and $options['schema']) {
            $schema = $options['schema'];
        }
        $payload = [
            'model' => $model,
            'prompt' => $prompt,
            'stream' => false,
            'options' => [
                'temperature' => $temperature,
            ]
        ];

        // Add max tokens if specified
        if (isset($options['max_tokens'])) {
            $payload['options']['num_predict'] = $options['max_tokens'];
        }

        if ($schema) {

            $payload['structured_outputs'] = true;
            $payload['format'] = 'json';
            $payload['type'] = 'array';
            $payload['items'] = $schema;

        }

        try {
            $response = $this->makeRequest($payload);



            $result = $response['response'] ?? '';
            if ($schema) {

                $result = $this->parseJson($result);
            }

            if(!$result){
                $result = [];
            }

            // Store in cache if caching is enabled
            if ($this->useCache && !empty($result)) {
                Cache::put($cacheKey, $result, $this->cacheDuration * 60);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Ollama API error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Format messages for Ollama API.
     *
     * @param array $messages
     * @return string
     */
    protected function formatMessagesForOllama(array $messages): string
    {
        $formattedPrompt = '';

        foreach ($messages as $message) {
            $role = $message['role'] ?? '';
            $content = $message['content'] ?? '';

            switch ($role) {
                case 'system':
                    $formattedPrompt .= "[SYSTEM]\n" . $content . "\n\n";
                    break;
                case 'user':
                    $formattedPrompt .= "[USER]\n" . $content . "\n\n";
                    break;
                case 'assistant':
                    $formattedPrompt .= "[ASSISTANT]\n" . $content . "\n\n";
                    break;
                default:
                    $formattedPrompt .= $content . "\n\n";
            }
        }

        $formattedPrompt .= "[ASSISTANT]\n";

        return trim($formattedPrompt);
    }

    /**
     * Make a request to the Ollama API.
     *
     * @param array $data The payload to send
     * @return array The response from Ollama
     * @throws \Exception If the request fails
     */
    protected function makeRequest(array $data): array
    {
        $ch = HttpClientFactory::curl($this->apiUrl, 300);

        $headers = ['Content-Type: application/json'];
        if (!empty($this->apiKey)) {
            $headers[] = 'Authorization: Bearer ' . $this->apiKey;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        return HttpClientFactory::executeCurlJson($ch, 'Ollama API');
    }
}
