<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Config;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class YouTubeTranscriptionTool extends BaseTool
{
    protected Client $client;

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_youtube_transcription',
            'Retrieve the transcription of a YouTube video using Supadata API'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'video_url',
                type: PropertyType::STRING,
                description: 'The URL of the YouTube video to transcribe',
                required: true,
            ),
            new ToolProperty(
                name: 'text_only',
                type: PropertyType::BOOLEAN,
                description: 'Whether to return only text without timestamps (default: true)',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from variadic args
        $videoUrl = $args['video_url'] ?? '';
        $textOnly = $args['text_only'] ?? true;
        
        $apiKey = Config::get('modules.ai.drivers.supadata.api_key');
        
        if (!$apiKey) {
            return 'Error: Supadata API key not configured. Please configure it in AI Settings.';
        }
        
        if (empty($videoUrl)) {
            return 'Error: Video URL parameter is required';
        }

        // Validate YouTube URL
        if (!$this->isValidYouTubeUrl($videoUrl)) {
            return 'Error: Invalid YouTube URL provided. Please provide a valid YouTube video URL.';
        }

        try {
            $response = $this->getClient($apiKey)->get('transcript', [
                'query' => [
                    'url' => $videoUrl,
                    'text' => $textOnly ? 'true' : 'false'
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                return "Error: Supadata API error: {$response->getBody()->getContents()}";
            }

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (!$responseData || !isset($responseData['content'])) {
                return 'Error: Invalid response from Supadata API';
            }

            $transcription = $responseData['content'];
            $metadata = [
                'source' => 'Supadata API',
                'text_only' => $textOnly,
                'processed_at' => now()->toISOString()
            ];

            return "Successfully transcribed YouTube video:\n\nVideo URL: {$videoUrl}\n\nTranscription:\n{$transcription}\n\nMetadata: " . json_encode($metadata, JSON_PRETTY_PRINT);

        } catch (RequestException $e) {
            return "Error: Failed to fetch transcription: " . $e->getMessage();
        } catch (\Exception $e) {
            return "Error: Unexpected error: " . $e->getMessage();
        }
    }

    protected function getClient(string $apiKey): Client
    {
        if (isset($this->client)) {
            return $this->client;
        }

        $this->client = new Client([
            'base_uri' => 'https://api.supadata.ai/v1/youtube/',
            'headers' => [
                'x-api-key' => $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'timeout' => 60, // YouTube transcription can take some time
        ]);

        return $this->client;
    }

    protected function isValidYouTubeUrl(string $url): bool
    {
        // Match various YouTube URL formats
        $patterns = [
            '/^https?:\/\/(www\.)?youtube\.com\/watch\?v=[\w-]+/',
            '/^https?:\/\/(www\.)?youtu\.be\/[\w-]+/',
            '/^https?:\/\/(www\.)?youtube\.com\/embed\/[\w-]+/',
            '/^https?:\/\/(www\.)?m\.youtube\.com\/watch\?v=[\w-]+/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }

        return false;
    }
}
