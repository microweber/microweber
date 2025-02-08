<?php

namespace Modules\AiWizard\Services\Contracts;

interface AiServiceInterface
{
    /**
     * Generate content using AI
     *
     * @param string $prompt The prompt to generate content from
     * @param array $options Additional options for the AI service
     * @return string The generated content
     */
    public function generateContent(string $prompt, array $options = []): string;

    /**
     * Get the name of the driver
     *
     * @return string
     */
    public function getDriverName(): string;
}
