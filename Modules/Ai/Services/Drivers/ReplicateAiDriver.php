<?php

namespace Modules\Ai\Services\Drivers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
    protected string $defaultImageModel = 'google/imagen-3';

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
        $this->defaultImageModel = $config['model'] ?? 'google/imagen-3';
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
     * Get file extension from URL
     *
     * @param string $url
     * @return string
     */
    protected function getFileExtensionFromUrl(string $url): string
    {
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'];

        // Extract extension from URL path
        $path = parse_url($url, PHP_URL_PATH);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // If no extension found or not allowed, default to png
        if (empty($extension) || !in_array($extension, $allowedExtensions)) {
            return 'png';
        }

        // Convert jpeg to jpg for consistency
        if ($extension === 'jpeg') {
            return 'jpg';
        }

        return $extension;
    }

    public function generateImage(array $messages, array $options = []): array
    {
        $prompt = implode(' ', array_column($messages, 'content'));
        $urls = [];

        if ($this->useCache) {
            $cacheKey = 'replicate_image_generate_' . md5($prompt . json_encode($options));
            if ($cached = Cache::get($cacheKey)) {
                return $cached;
            }
        }

        if(isset($options['number_of_images'])){
            $options['number_of_images'] = intval($options['number_of_images']);
        }

        $model = $options['model'] ?? $this->defaultImageModel;
        $number_of_images = $options['number_of_images'] ?? 1;
        $defaultParams = $this->config['default_parameters'] ?? [];
        $fieldMapping = $this->config['field_mapping'][$model] ?? [];

        $payload = ['input' => ['prompt' => $prompt]];

        // Apply default parameters first
        foreach ($defaultParams as $key => $defaultValue) {
            $payload['input'][$key] = $options[$key] ?? $defaultValue;
        }

        // Apply field mappings
        if (!empty($fieldMapping)) {
            foreach ($options as $key => $value) {
                if (isset($fieldMapping[$key])) {
                    $mappedKey = $fieldMapping[$key];
                    $payload['input'][$mappedKey] = $value;
                }
            }
        }

        // Validate aspect_ratio if provided
        if (isset($payload['input']['aspect_ratio']) && !empty($this->config['supported_aspect_ratio'])) {
            $providedAspectRatio = $payload['input']['aspect_ratio'];
            if (!in_array($providedAspectRatio, $this->config['supported_aspect_ratio'])) {
                // Aspect ratio is invalid, remove it or use default
                unset($payload['input']['aspect_ratio']);
                // If default is available, reapply it
                if (isset($defaultParams['aspect_ratio'])) {
                    $payload['input']['aspect_ratio'] = $defaultParams['aspect_ratio'];
                }
            }
        }

        // Set number of images if the model supports it
        if ($number_of_images > 1) {
            // Different models use different parameter names
            $numImagesParam = $fieldMapping['number_of_images'] ?? 'number_of_images';
            $payload['input'][$numImagesParam] = intval($number_of_images);
        }

        $payload['input']['output_format'] = $options['output_format'] ?? 'png';

        try {
            // Use the correct endpoint format for the model
            $endpoint = "/v1/models/{$model}/predictions";

            $response = $this->makeRequest($endpoint, $payload);

            // Handle different response formats - some models return output as array, others as string
            $imageUrls = [];
            if (isset($response['output'])) {
                if (is_array($response['output'])) {
                    // If output is an array (like in stability-ai models)
                    $imageUrls = $response['output'];
                } elseif (is_string($response['output'])) {
                    // If output is a string URL (like in some models)
                    $imageUrls = [$response['output']];
                }
            }

            // Store each image to disk if URLs are available
            if (!empty($imageUrls)) {
                // Create directory if it doesn't exist
                $directory = 'media/replicate';
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }

                $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'];

                foreach ($imageUrls as $index => $imageUrl) {
                    // Get file extension from URL
                    $extension = $this->getFileExtensionFromUrl($imageUrl);

                    // Validate the extension
                    if (!in_array($extension, $allowedExtensions)) {
                        continue; // Skip invalid extensions
                    }

                    // Create a unique filename with correct extension
                    $imagePath = $directory . '/' . md5($prompt . microtime() . $index) . '.' . $extension;

                    // Download and store the image
                    $imageContent = $this->fetchImageContent($imageUrl);
                    Storage::disk('public')->put($imagePath, $imageContent);

                    // Add the public URL to the urls array
                    $urls[] = Storage::disk('public')->url($imagePath);

                }

                // Add URLs and data to the response
                $response['urls'] = $urls;
                //$response['data'] = $baseData;

                // Keep single URL for backward compatibility
                if (!empty($urls)) {
                    $response['url'] = $urls[0];
                }

            }

            // Store in cache if caching is enabled
            if ($this->useCache && !empty($imageUrls)) {
                Cache::put($cacheKey, $response, $this->cacheDuration * 60);
            }

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch image content from URL
     *
     * @param string $url The image URL to fetch
     * @return string Image content
     * @throws \Exception
     */
    protected function fetchImageContent(string $url): string
    {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            $imageData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);

            curl_close($ch);

            if ($error) {
                throw new \Exception("cURL Error when downloading image: $error");
            }

            if ($httpCode >= 400) {
                throw new \Exception("Error downloading image, HTTP code: $httpCode");
            }

            return $imageData;
        } catch (\Exception $e) {
            throw new \Exception("Failed to download image: " . $e->getMessage());
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
