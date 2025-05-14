<?php

namespace Modules\Ai\Services\Drivers;

interface AiServiceInterface
{
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
    public function sendToChat(array $messages, array $options = []): string|array;

    /**
     * Get the name of the currently active AI driver.
     *
     * @return string
     */
    public function getActiveDriver(): string;

    /**
     * Set the active AI driver.
     *
     * @param string $driver
     * @return void
     */
    public function setActiveDriver(string $driver): void;
}
