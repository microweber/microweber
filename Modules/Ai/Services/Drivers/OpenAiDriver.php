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
     * Send messages to chat and get a response.
     *
     * @param array $messages Array of messages in the format:
     *                       [
     *                           ['role' => 'system', 'content' => 'System message'],
     *                           ['role' => 'user', 'content' => 'User message'],
     *                           ['role' => 'assistant', 'content' => 'Assistant response'],
     *                           ['role' => 'function', 'name' => 'function_name', 'content' => 'Function response']
     *                       ]
     * @param array $options Additional options including:
     *                      - functions: Array of function definitions for the AI to call
     *                      - function_call: Optional specific function to call
     *                      - model: AI model to use
     *                      - temperature: Sampling temperature
     *                      - max_tokens: Maximum tokens in response
     * @return string|array The generated content or function call response array containing:
     *                      ['function_call' => object, 'content' => ?string]
     */
    public function sendToChat(array $messages, array $options = [], ?array $schema = null): string|array
    {
        $params = [
            'model' => $options['model'] ?? 'gpt-3.5-turbo',
            'messages' => $messages,
            'temperature' => $options['temperature'] ?? 0.7,
            'max_tokens' => $options['max_tokens'] ?? 1000,
        ];

        // Add functions if provided
        if (!empty($options['functions'])) {
            $params['functions'] = $options['functions'];

            if (isset($options['function_call'])) {
                $params['function_call'] = $options['function_call'];
            }
        }

        $response = $this->client->chat()->create($params);

        // Handle function calls in the response
        if (isset($response->choices[0]->message->function_call)) {
            return [
                'function_call' => $response->choices[0]->message->function_call,
                'content' => null
            ];
        }

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
