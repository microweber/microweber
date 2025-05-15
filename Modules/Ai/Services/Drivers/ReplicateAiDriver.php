<?php

namespace Modules\Ai\Services\Drivers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ReplicateAiDriver extends BaseDriver implements AiImageServiceInterface
{
    /**
     * The Replicate API token.
     *
     * @var string
     */
    protected string $apiToken;

    /**
     * The API endpoint for Replicate.
     *
     * @var string
     */
    protected string $apiEndpoint = 'https://api.replicate.com';

    /**
     * Default model for image generation.
     *
     * @var string
     */
    protected string $defaultImageModel = 'stability-ai/stable-diffusion-3.5-medium';

    /**
     * Whether to use caching.
     *
     * @var bool
     */
    protected bool $useCache;

    /**
     * Cache duration in minutes.
     *
     * @var int
     */
    protected int $cacheDuration;

    /**
     * Create a new Replicate AI driver instance.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->apiToken = $config['api_key'] ?? env('REPLICATE_API_TOKEN');
        $this->defaultImageModel = $config['model'] ?? 'stability-ai/stable-diffusion-3.5-medium';
        $this->useCache = $config['use_cache'] ?? false;
        $this->cacheDuration = $config['cache_duration'] ?? 600;

        if (isset($config['api_endpoint'])) {
            $this->apiEndpoint = rtrim($config['api_endpoint'], '/');
        }
    }

    /**
     * Get the name of this driver.
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'replicate';
    }

    /**
     * Generate an image with a text prompt.
     *
     * @param string $prompt The text prompt
     * @param array $options Additional options for image generation
     * @return array Response containing image URLs or error
     * @throws \Exception
     */
    public function generateImage(string $prompt, array $options = []): array
    {
        // Check cache first if caching is enabled
        if ($this->useCache) {
            $cacheKey = 'replicate_image_' . md5($prompt . json_encode($options));
            if ($cached = Cache::get($cacheKey)) {
                return $cached;
            }
        }

        $model = $options['model'] ?? $this->defaultImageModel;

        $payload = [
            'input' => [
                'prompt' => $prompt,
            ]
        ];

        // Add model-specific parameters
        if (strpos($model, 'stability-ai/stable-diffusion') !== false) {
            // Add specific parameters for Stability AI models
            if (isset($options['negative_prompt'])) {
                $payload['input']['negative_prompt'] = $options['negative_prompt'];
            }
        } else {
            // Default parameters for other models
            $payload['input']['aspect_ratio'] = $options['aspect_ratio'] ?? '3:4';
            $payload['input']['number_of_images'] = $options['number_of_images'] ?? 1;
            $payload['input']['prompt_optimizer'] = $options['prompt_optimizer'] ?? true;

            // Add additional options if provided
            if (isset($options['guidance_scale'])) {
                $payload['input']['guidance_scale'] = $options['guidance_scale'];
            }

            if (isset($options['negative_prompt'])) {
                $payload['input']['negative_prompt'] = $options['negative_prompt'];
            }
        }

        try {
            // Use the correct endpoint format for the model
            $endpoint = "/v1/models/{$model}/predictions";

            $response = $this->makeRequest($endpoint, $payload);

            // Store in cache if caching is enabled
            if ($this->useCache && isset($response['output'])) {
                Cache::put($cacheKey, $response, $this->cacheDuration * 60);
            }

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Make an API request to Replicate.
     *
     * @param string $endpoint The endpoint path
     * @param array $data The payload to send
     * @param string $method HTTP method (POST, GET, etc.)
     * @return array The response data
     * @throws \Exception
     */
    protected function makeRequest(string $endpoint, array $data = [], string $method = 'POST'): array
    {
        // Ensure endpoint starts with a slash
        if (substr($endpoint, 0, 1) !== '/') {
            $endpoint = '/' . $endpoint;
        }

        $url = $this->apiEndpoint . $endpoint;

        $headers = [
            'Authorization: Bearer ' . $this->apiToken,
            'Content-Type: application/json',
            'Prefer: wait' // This makes the API wait for the prediction to complete
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300); // 5 minutes timeout

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
            throw new \Exception("Replicate API returned error code: $httpCode, Response: $result");
        }

        $decodedResult = json_decode($result, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Failed to decode JSON response: " . json_last_error_msg());
        }

        return $decodedResult;
    }

    /**
     * Send messages to chat and get a response.
     * Not implemented for this driver as it's primarily for image generation.
     *
     * @param array $messages Array of messages
     * @param array $options Additional options
     * @return string|array
     * @throws \Exception
     */
    public function sendToChat(array $messages, array $options = []): string|array
    {
        throw new \Exception('Chat functionality is not supported by the Replicate driver');
    }
}

