<?php

namespace Modules\Ai\Services\Drivers;

use OpenAI\Client;
use OpenAI\Factory;
use Illuminate\Support\Facades\Cache;

class OpenAiDriver extends BaseDriver
{
    /**
     * The OpenAI client instance.
     *
     * @var Client
     */
    protected Client $client;

    protected $model = 'gpt-4o-mini';

    /**
     * Whether to use caching or not.
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
     * Create a new OpenAI driver instance.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        if (isset($config['model'])) {
            $this->model = $config['model'];
        } else {
            $this->model = config('modules.ai.drivers.openai.model', 'gpt-4o-mini');
        }

        // Initialize cache configuration
        $this->useCache = $config['use_cache'] ?? config('modules.ai.drivers.openai.use_cache', true);
        $this->cacheDuration = $config['cache_duration'] ?? config('modules.ai.drivers.openai.cache_duration', 60);

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
     *                      - tools: Array of tool definitions (newer API format)
     *                      - tool_choice: Optional specific tool to use
     *                      - model: AI model to use
     *                      - temperature: Sampling temperature
     *                      - max_tokens: Maximum tokens in response
     * @param array|null $schema JSON schema for response formatting
     * @return string|array The generated content or function call response array containing:
     *                      ['function_call' => object, 'content' => ?string]
     *                      or a JSON formatted response based on the provided schema
     */
    public function sendToChat(array $messages, array $options = []): string|array
    {
        // Check cache first if caching is enabled
        if ($this->useCache) {
            $cacheKey = 'openai_' . md5(json_encode($messages) . json_encode($options));
            if ($cached = Cache::get($cacheKey)) {
                return $cached;
            }
        }

        $params = [
            'model' => $options['model'] ?? $this->model ?? 'gpt-4o-mini',
            'messages' => $messages,
            'temperature' => $options['temperature'] ?? 0.0,
            'max_tokens' => $options['max_tokens'] ?? null,
            'top_p' => $options['top_p'] ?? 1.0,
            'frequency_penalty' => $options['frequency_penalty'] ?? 0.0,
            'presence_penalty' => $options['presence_penalty'] ?? 0.0,
            'stop' => $options['stop'] ??  null,
        ];

        $schema = null;
        if (isset($options['schema']) and $options['schema']) {
            $schema = $options['schema'];
        }

        // Add JSON schema response format if provided
        if ($schema !== null) {

            $params['response_format'] = [
                'type' => 'json_schema',
                //          'name' => 'json_schema',
                'json_schema' => ['name' => 'json_schema', 'schema' => $schema],
            ];
        }

        // Add functions if provided (legacy method)
        if (!empty($options['functions'])) {
            $params['functions'] = $options['functions'];

            if (isset($options['function_call'])) {
                $params['function_call'] = $options['function_call'];
            }
        }

        // Add tools if provided (newer method)
        if (!empty($options['tools'])) {
            $params['tools'] = $options['tools'];

            if (isset($options['tool_choice'])) {
                $params['tool_choice'] = $options['tool_choice'];
            }
        }

        $response = $this->client->chat()->create($params);
        $result = null;

        // Handle function calls in the response
        if (isset($response->choices[0]->message->function_call)) {
            $result = [
                'function_call' => $response->choices[0]->message->function_call,
                'content' => null
            ];
        }
        // Handle tool calls in the response (newer API)
        else if (isset($response->choices[0]->message->tool_calls)) {
            $result = [
                'tool_calls' => $response->choices[0]->message->tool_calls,
                'content' => null
            ];
        }
        // Return content, which may be JSON formatted if schema was provided
        else {
            $result = $response->choices[0]->message->content;
        }

        // Store in cache if caching is enabled
        if ($this->useCache) {
            Cache::put($cacheKey, $result, $this->cacheDuration * 60);
        }

        return $result;
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
