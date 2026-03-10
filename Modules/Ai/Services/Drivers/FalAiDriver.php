<?php

namespace Modules\Ai\Services\Drivers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FalAiDriver extends BaseDriver implements AiImageServiceInterface
{
    /**
     * The FAL API key.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * The API endpoint for FAL.
     *
     * @var string
     */
    protected string $apiEndpoint = 'https://fal.run';

    /**
     * Default model for image generation.
     *
     * @var string
     */
    protected string $defaultImageModel = 'fal-ai/fast-sdxl';

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
     * Request timeout in seconds.
     *
     * @var int
     */
    protected int $timeout;

    /**
     * Create a new FAL AI driver instance.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->apiKey = $config['api_key'] ?? env('FAL_API_KEY');
        $this->defaultImageModel = $config['model'] ?? 'fal-ai/fast-sdxl';
        $this->useCache = $config['use_cache'] ?? false;
        $this->cacheDuration = $config['cache_duration'] ?? 600;
        $this->timeout = $config['timeout'] ?? 300;

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
        return 'fal';
    }

    /**
     * Generate an image based on the provided prompt and options.
     *
     * @param array $messages The prompt for image generation.
     * @param array $options Additional options for image generation.
     * @return array The generated image data.
     */
    public function generateImage(array $messages, array $options = []): array
    {
        $prompt = implode(' ', array_column($messages, 'content'));
        $urls = [];

        if ($this->useCache) {
            $cacheKey = 'fal_image_generate_' . md5($prompt . json_encode($options));
            if ($cached = Cache::get($cacheKey)) {
                return $cached;
            }
        }

        $model = $options['model'] ?? $this->defaultImageModel;
        $subpath = $options['subpath'] ?? null;

        // Build the endpoint URL
        $endpoint = $model;
        if ($subpath) {
            $endpoint .= '/' . ltrim($subpath, '/');
        }

        // Prepare the payload
        $payload = [
            'prompt' => $prompt,
            'sync_mode' => true,
        ];

        // Add optional parameters
        if (isset($options['image_size'])) {
            $payload['image_size'] = $options['image_size'];
        }

        if (isset($options['num_inference_steps'])) {
            $payload['num_inference_steps'] = intval($options['num_inference_steps']);
        }

        if (isset($options['guidance_scale'])) {
            $payload['guidance_scale'] = floatval($options['guidance_scale']);
        }

        if (isset($options['negative_prompt'])) {
            $payload['negative_prompt'] = $options['negative_prompt'];
        }

        if (isset($options['seed'])) {
            $payload['seed'] = intval($options['seed']);
        }

        if (isset($options['safety_tolerance'])) {
            $payload['safety_tolerance'] = intval($options['safety_tolerance']);
        }

        if (isset($options['num_images'])) {
            $payload['num_images'] = intval($options['num_images']);
        }


        $response = $this->makeRequest($endpoint, $payload);

        // Process the response and handle images (URLs or base64)
        $imageData = [];
        if (isset($response['images']) && is_array($response['images'])) {
            foreach ($response['images'] as $image) {
                if (isset($image['url'])) {
                    $imageData[] = $image['url'];
                }
            }
        }

        // Store each image to disk
        if (!empty($imageData)) {
            // Create directory if it doesn't exist
            $directory = 'media/fal';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            foreach ($imageData as $index => $imageUrl) {
                // Check if this is a base64 data URL
                if (strpos($imageUrl, 'data:image/') === 0) {
                    // Handle base64 encoded image
                    $this->saveBase64Image($imageUrl, $directory, $prompt, $index, $urls);
                } else {
                    // Handle regular URL
                    $this->saveUrlImage($imageUrl, $directory, $prompt, $index, $urls);
                }
            }

            // Add URLs to the response
            $response['urls'] = $urls;

            // Keep single URL for backward compatibility
            if (!empty($urls)) {
                $response['url'] = $urls[0];
            }
        }

        // Store in cache if caching is enabled
        if ($this->useCache && !empty($imageData)) {
            Cache::put($cacheKey, $response, $this->cacheDuration * 60);
        }

        return $response;
    }

    /**
     * Save base64 encoded image to disk
     *
     * @param string $base64Data The base64 data URL
     * @param string $directory The directory to save to
     * @param string $prompt The prompt used for filename
     * @param int $index The image index
     * @param array &$urls Array to add the public URL to
     * @return void
     */
    protected function saveBase64Image(string $base64Data, string $directory, string $prompt, int $index, array &$urls): void
    {
        try {
            // Extract the image data from the data URL
            $parts = explode(',', $base64Data, 2);
            if (count($parts) !== 2) {
                throw new \Exception("Invalid base64 data URL format");
            }

            // Get the header (e.g., "data:image/jpeg;base64")
            $header = $parts[0];
            $imageContent = base64_decode($parts[1]);

            if ($imageContent === false) {
                throw new \Exception("Failed to decode base64 image data");
            }

            // Extract the image type from the header
            preg_match('/data:image\/([a-zA-Z0-9]+);base64/', $header, $matches);
            $extension = isset($matches[1]) ? $matches[1] : 'jpeg';

            // Normalize extension
            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }

            // Create a unique filename
            $imagePath = $directory . '/' . md5($prompt . microtime() . $index) . '.' . $extension;

            // Save the image
            Storage::disk('public')->put($imagePath, $imageContent);

            // Add the public URL to the urls array
            $urls[] = Storage::disk('public')->url($imagePath);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Save image from URL to disk
     *
     * @param string $imageUrl The image URL
     * @param string $directory The directory to save to
     * @param string $prompt The prompt used for filename
     * @param int $index The image index
     * @param array &$urls Array to add the public URL to
     * @return void
     */
    protected function saveUrlImage(string $imageUrl, string $directory, string $prompt, int $index, array &$urls): void
    {
        try {
            // Get file extension from URL
            $extension = $this->getFileExtensionFromUrl($imageUrl);

            // Create a unique filename with correct extension
            $imagePath = $directory . '/' . md5($prompt . microtime() . $index) . '.' . $extension;

            // Download and store the image
            $imageContent = $this->fetchImageContent($imageUrl);
            Storage::disk('public')->put($imagePath, $imageContent);

            // Add the public URL to the urls array
            $urls[] = Storage::disk('public')->url($imagePath);

        } catch (\Exception $e) {

            throw $e;
        }
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
            return 'jpeg'; // FAL typically returns JPEG images
        }

        // Convert jpeg to jpg for consistency
        if ($extension === 'jpeg') {
            return 'jpg';
        }

        return $extension;
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
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

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
     * Make a synchronous API request to FAL.
     *
     * @param string $endpoint The model endpoint (e.g., 'fal-ai/fast-sdxl' or 'fal-ai/flux/dev')
     * @param array $data The payload to send
     * @return array The response data
     * @throws \Exception
     */
    protected function makeRequest(string $endpoint, array $data = []): array
    {
        $url = $this->apiEndpoint . '/' . ltrim($endpoint, '/');

        $headers = [
            'Authorization: Key ' . $this->apiKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Add SSL options for better compatibility
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            throw new \Exception("cURL Error: $error");
        }

        if ($httpCode >= 400) {
            $errorMessage = "FAL API returned error code: $httpCode";
            if ($result) {
                $errorData = json_decode($result, true);
                if (isset($errorData['detail'])) {
                    $errorMessage .= ", Error: " . $errorData['detail'];
                } else {
                    $errorMessage .= ", Response: $result";
                }
            }
            throw new \Exception($errorMessage);
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
        throw new \Exception('Chat functionality is not supported by the FAL AI driver. This driver is for image generation only.');
    }
}
