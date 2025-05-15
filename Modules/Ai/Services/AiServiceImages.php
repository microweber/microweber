<?php

namespace Modules\Ai\Services;

use InvalidArgumentException;
use Modules\Ai\Services\Drivers\AiServiceInterface;
use Modules\Ai\Services\Drivers\GeminiAiDriver;
use Modules\Ai\Services\Drivers\OllamaAiDriver;
use Modules\Ai\Services\Drivers\OpenAiDriver;
use Modules\Ai\Services\Drivers\ReplicateAiDriver;

class AiServiceImages
{
    /**
     * The active driver instance.
     *
     * @var object
     */
    protected $driver;

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
     * Create a new AI service images instance.
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
     * @return object
     *
     * @throws InvalidArgumentException
     */
    protected function createDriver(string $driver, array $config): object
    {
        if (isset($this->drivers[$driver])) {
            return $this->drivers[$driver];
        }

        $driverClass = match ($driver) {
            'replicate' => ReplicateAiDriver::class,
            'gemini' => GeminiAiDriver::class,

            default => throw new InvalidArgumentException("Driver [{$driver}] not supported for image generation."),
        };

        return $this->drivers[$driver] = new $driverClass($config);
    }

    /**
     * Generate an image based on a prompt.
     *
     * @param string $prompt The text prompt for image generation
     * @param array $options Additional options specific to the driver
     * @return array Response containing image URLs or data
     * @throws \Exception
     */
    public function generateImage(string $prompt, array $options = []): array
    {
        // Check if the current driver is enabled
        $driverName = $this->getActiveDriver();
        $isEnabled = $this->config[$driverName]['enabled'] ?? false;

        if (!$isEnabled) {
            throw new \Exception("AI driver '$driverName' is not enabled. Please enable it in the settings.");
        }

        // Different drivers have different methods for image generation
        if ($driverName === 'openai') {
            return $this->driver->images()->create([
                'prompt' => $prompt,
                'n' => $options['number_of_images'] ?? 1,
                'size' => $options['size'] ?? '1024x1024',
                'model' => $options['model'] ?? 'dall-e-3',
                'quality' => $options['quality'] ?? 'standard',
            ]);
        } elseif ($driverName === 'gemini') {
            return $this->driver->processImageWithPrompt($prompt, $options['imageBase64'] ?? '', $options);
        } elseif ($driverName === 'replicate') {
            return $this->driver->generateImage($prompt, $options);
        }

        throw new \Exception("Image generation not supported by driver: $driverName");
    }

    /**
     * Get the name of the currently active AI driver.
     *
     * @return string
     */
    public function getActiveDriver(): string
    {
        return $this->driver->getDriverName();
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
