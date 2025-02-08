<?php

namespace Modules\AiWizard\Services\Drivers;

use OpenAI\Laravel\Facades\OpenAI;

class OpenAiDriver extends BaseDriver
{
    /**
     * Generate content using OpenAI
     *
     * @param string $prompt The prompt to generate content from
     * @param array $options Additional options for the AI service
     * @return string The generated content
     */
    public function generateContent(string $prompt, array $options = []): string
    {
        $model = $this->config('model', 'gpt-3.5-turbo');
        $maxTokens = $this->config('max_tokens', 1000);
        $temperature = $this->config('temperature', 0.7);

        $result = OpenAI::chat()->create([
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant that generates website content.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
        ]);

        return $result->choices[0]->message->content;
    }

    /**
     * Get the name of the driver
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'openai';
    }
}
