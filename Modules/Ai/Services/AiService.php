<?php

namespace Modules\Ai\Services;

use InvalidArgumentException;
use Modules\Ai\Services\Drivers\AiServiceInterface;
use Modules\Ai\Services\Drivers\GeminiAiDriver;
use Modules\Ai\Services\Drivers\OllamaAiDriver;
use Modules\Ai\Services\Drivers\OpenAiDriver;
use Modules\Ai\Services\Drivers\OpenRouterAiDriver;

class AiService implements AiServiceInterface
{
    /**
     * The active driver instance.
     *
     * @var \Modules\Ai\Services\Drivers\AiServiceInterface
     */
    protected AiServiceInterface $driver;

    /**
     * The available driver instances.
     *
     * @var array
     */
    protected array $drivers = [];

    /**
     * The configuration for all drivers.
     *
     * @var array
     */
    protected array $config = [];

    /**
     * Create a new AI service instance.
     *
     * @param string $defaultDriver
     * @param array $config
     */
    public function __construct(string $defaultDriver, array $config)
    {
        $this->config = $config;
        $this->driver = $this->createDriver($defaultDriver, $config[$defaultDriver] ?? []);
    }

    /**
     * Create a new driver instance.
     *
     * @param string $driver
     * @param array $config
     * @return \Modules\Ai\Services\Drivers\AiServiceInterface
     *
     * @throws InvalidArgumentException
     */
    protected function createDriver(string $driver, array $config): AiServiceInterface
    {
        if (isset($this->drivers[$driver])) {
            return $this->drivers[$driver];
        }

        $driverClass = match ($driver) {
            'openai' => OpenAiDriver::class,
            'openrouter' => OpenRouterAiDriver::class,
            'gemini' => GeminiAiDriver::class,
            'ollama' => OllamaAiDriver::class,
            //todo add more drivers
            default => throw new InvalidArgumentException("Driver [{$driver}] not supported."),
        };

        return $this->drivers[$driver] = new $driverClass($config);
    }

    /**
     * Send messages to chat and get a response.
     *
     * @param array $messages Array of messages
     * @param array $options Additional options
     * @return string|array The generated content or function call response array
     */
    public function sendToChat(array $messages, array $options = []): string|array
    {
        // Check if the current driver is enabled
        $driverName = $this->driver->getActiveDriver();
        $isEnabled = $this->config[$driverName]['enabled'] ?? false;

        if (!$isEnabled) {
            throw new \Exception("AI driver '$driverName' is not enabled. Please enable it in the settings.");
        }

        return $this->driver->sendToChat($messages, $options);
    }

    /**
     * Process an image with AI (using the default image driver)
     *
     * @param string $prompt Text prompt for image generation
     * @param array $options Additional options
     * @return mixed Response from the AI image model
     */
    public function processImage(string $prompt, array $options = [])
    {
        $imageDriver = config('modules.ai.default_driver_images', 'gemini');
        $driverConfig = $this->config[$imageDriver] ?? [];

        // Check if the image driver is enabled
        if (empty($driverConfig['enabled'])) {
            throw new \Exception("AI image driver '$imageDriver' is not enabled. Please enable it in the settings.");
        }

        // Check if the driver supports images
        if (empty($driverConfig['supports_images'])) {
            throw new \Exception("AI driver '$imageDriver' does not support image generation.");
        }

        // Create the image driver if not already loaded
        if (!isset($this->drivers[$imageDriver])) {
            $this->drivers[$imageDriver] = $this->createDriver($imageDriver, $driverConfig);
        }

        // Use the appropriate method based on the driver
        if ($imageDriver === 'gemini') {
            // For Gemini, processImageWithPrompt is implemented in the GeminiAiDriver
            return $this->drivers[$imageDriver]->processImageWithPrompt($prompt, $options);
        } elseif ($imageDriver === 'openai') {
            // Implement DALL-E image generation here or call a specific method on OpenAiDriver
            // This is just a placeholder - implementation would depend on how OpenAI driver handles images
            return $this->drivers[$imageDriver]->generateImage($prompt, $options);
        }

        throw new \Exception("Image processing not implemented for driver '$imageDriver'");
    }

    /**
     * Get the name of the currently active AI driver.
     *
     * @return string
     */
    public function getActiveDriver(): string
    {
        return $this->driver->getActiveDriver();
    }

    /**
     * Set the active AI driver.
     *
     * @param string $driver
     * @return void
     */
    public function setActiveDriver(string $driver): void
    {
        $this->driver = $this->createDriver($driver, $this->config[$driver] ?? []);
    }
}
