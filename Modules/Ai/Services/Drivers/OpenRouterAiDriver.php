<?php

namespace Modules\Ai\Services\Drivers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OpenRouterAiDriver extends BaseDriver
{
    protected string $apiKey;
    protected string $apiEndpoint;
    protected string $defaultModel;
    protected bool $useCache;
    protected int $cacheDuration;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->apiKey = $config['api_key'] ?? null;
        $this->apiEndpoint = rtrim($config['api_endpoint'] ?? 'https://openrouter.ai/api/v1/', '/');
        $this->defaultModel = $config['model'] ?? 'meta-llama/llama-3-8b-instruct';
        $this->useCache = $config['use_cache'] ?? false;
        $this->cacheDuration = $config['cache_duration'] ?? 600;
    }

    public function getDriverName(): string
    {
        return 'openrouter';
    }

    public function sendToChat(array $messages, array $options = []): string|array
    {

        if ($this->useCache) {
            $cacheKey = 'openrouter_' . md5(json_encode($messages) . json_encode($options));
            if ($cached = Cache::get($cacheKey)) {

                return $cached;
            }
        }

        $schema = null;
        if (isset($options['schema']) and $options['schema']) {
            $schema = $options['schema'];
        }

        $payload = [
            'model' => $options['model'] ?? $this->defaultModel,
            'messages' => $messages,
            'max_tokens' => $options['max_tokens'] ?? null,
        ];

        if ($schema) {

           $payload['structured_outputs'] = true;
            $payload['format'] = 'json';
            $payload['type'] = 'array';
            $payload['items'] = $schema;
         //$payload['response_format'] = 'json';
//             $payload['response_format'] = [
//                'type' => 'json_object',
//                'schema' => $schema
//            ];
        }


        if (!empty($options['functions'])) {
            $payload['functions'] = $options['functions'];
            if (isset($options['function_call'])) {
                $payload['function_call'] = $options['function_call'];
            }
        }

        try {
            $response = $this->makeRequest('/chat/completions', $payload);
  //
            $content = $response['choices'][0]['message']['content'] ?? '';



            //check if it has ```json nd parse it
            if ($schema) {
                if (str_contains($content, '```json')) {
                    $content = str_replace('```json', '', $content);
                    $content = str_replace('```', '', $content);
                    $content = json_decode($content, true);
                } else {
                    $content = json_decode($content, true);
                }

            }


            // Handle function calls if present
            if (isset($response['choices'][0]['message']['function_call'])) {
                $result = [
                    'function_call' => $response['choices'][0]['message']['function_call'],
                    'content' => null
                ];
            } else {
                $result = $content;
            }

            if ($this->useCache) {
                Cache::put($cacheKey, $result, $this->cacheDuration * 60);
            }

            return $result;
        } catch (\Exception $e) {

            throw $e;
        }
    }

    protected function makeRequest(string $endpoint, array $data = [], string $method = 'POST')
    {
        $url = $this->apiEndpoint . $endpoint;

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
            'HTTP-Referer: ' . config('services.openrouter.referer', 'https://github.com/microweber/microweber'),
            'X-Title: ' . config('services.openrouter.title', 'Microweber'),
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10000);

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
        $result = trim($result);
        if ($httpCode >= 400) {
            throw new \Exception("API returned error code: $httpCode, Response: $result");
        }

        return json_decode($result, true);
    }
}
