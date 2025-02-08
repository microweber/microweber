<?php

namespace Modules\Ai\Services\Contracts;

interface AiServiceInterface
{
    /**
     * Generate content using AI based on the provided prompt.
     *
     * @param string $prompt The prompt to generate content from
     * @param array $options Additional options for content generation
     * @return string The generated content
     */
    public function generateContent(string $prompt, array $options = []): string;

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
