<?php

namespace Modules\Ai\Services\Drivers;

use OpenAI\Client;
use OpenAI\Factory;

class OpenAiDriver extends BaseDriver
{
    /**
     * The OpenAI client instance.
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Create a new OpenAI driver instance.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->client = (new Factory)->withApiKey(
            $config['api_key'] ?? env('OPENAI_API_KEY')
        )->make();
    }

    /**
     * Generate content using OpenAI's API.
     *
     * @param string $prompt
     * @param array $options
     * @return string
     */
    public function generateContent(string $prompt, array $options = []): string
    {
        $response = $this->client->chat()->create([
            'model' => $options['model'] ?? 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional content creator.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => $options['temperature'] ?? 0.7,
            'max_tokens' => $options['max_tokens'] ?? 1000,
        ]);

        return $response->choices[0]->message->content;
    }

    /**
     * Get the name of this driver.
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'openai';
    }
}
