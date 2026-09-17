<?php

namespace Modules\Ai\Services;

use InvalidArgumentException;
use Modules\Ai\Services\Drivers\AiServiceInterface;
use Modules\Ai\Services\Drivers\FalAiDriver;
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
            'fal' => FalAiDriver::class,
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
    public function generateImage(array $messages, array $options = []): string|array
    {
        // Check if the current driver is enabled
        $driverName = $this->getActiveDriver();
        $isEnabled = $this->config[$driverName]['enabled'] ?? false;

        if (!$isEnabled) {
            throw new \Exception("AI driver '$driverName' is not enabled. Please enable it in the settings.");
        }

        // Different drivers have different methods for image generation
        if ($driverName === 'openai') {
//            return $this->driver->images()->create([
//                'prompt' => $prompt,
//                'n' => $options['number_of_images'] ?? 1,
//                'size' => $options['size'] ?? '1024x1024',
//                'model' => $options['model'] ?? 'dall-e-3',
//                'quality' => $options['quality'] ?? 'standard',
//            ]);
        } elseif ($driverName === 'gemini') {
    //        return $this->driver->processImageWithPrompt($prompt, $options['imageBase64'] ?? '', $options);
        } elseif ($driverName === 'replicate') {
            return $this->driver->generateImage($messages, $options);
        } elseif ($driverName === 'fal') {
            return $this->driver->generateImage($messages, $options);
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
     * Whether image generation can actually run right now: the active driver is
     * one that implements generateImage (only replicate + fal do — openai/gemini
     * are stubbed) AND it is enabled in config. Used to gate the "+ Generate" UI
     * so it never offers a dead action.
     */
    public function isImageGenerationAvailable(): bool
    {
        try {
            $driverName = $this->getActiveDriver();
        } catch (\Throwable $e) {
            return false;
        }

        if (!in_array($driverName, ['replicate', 'fal'], true)) {
            return false;
        }

        return (bool) ($this->config[$driverName]['enabled'] ?? false);
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
