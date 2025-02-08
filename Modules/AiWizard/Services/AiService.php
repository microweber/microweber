<?php

namespace Modules\AiWizard\Services;

use Illuminate\Support\Manager;
use Modules\AiWizard\Services\Contracts\AiServiceInterface;
use Modules\AiWizard\Services\Drivers\OpenAiDriver;

class AiService extends Manager implements AiServiceInterface
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('aiwizard.default', 'openai');
    }

    /**
     * Create an instance of the OpenAI driver.
     *
     * @return OpenAiDriver
     */
    protected function createOpenaiDriver(): OpenAiDriver
    {
        return new OpenAiDriver($this->config->get('aiwizard.drivers.openai', []));
    }

    /**
     * Generate content using the current driver
     *
     * @param string $prompt The prompt to generate content from
     * @param array $options Additional options for the AI service
     * @return string The generated content
     */
    public function generateContent(string $prompt, array $options = []): string
    {
        return $this->driver()->generateContent($prompt, $options);
    }

    /**
     * Get the name of the current driver
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return $this->getDefaultDriver();
    }
}
