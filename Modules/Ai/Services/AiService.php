<?php

namespace Modules\Ai\Services;

use Modules\Ai\Services\Contracts\AiServiceInterface;
use Modules\Ai\Services\Drivers\OpenAiDriver;
use InvalidArgumentException;

class AiService implements AiServiceInterface
{
    /**
     * The active driver instance.
     *
     * @var AiServiceInterface
     */
    protected AiServiceInterface $driver;

    /**
     * The available driver instances.
     *
     * @var array
     */
    protected array $drivers = [];

    /**
     * Create a new AI service instance.
     *
     * @param string $defaultDriver
     * @param array $config
     */
    public function __construct(string $defaultDriver, array $config)
    {
        $this->driver = $this->createDriver($defaultDriver, $config[$defaultDriver] ?? []);
    }

    /**
     * Create a new driver instance.
     *
     * @param string $driver
     * @param array $config
     * @return AiServiceInterface
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
            default => throw new InvalidArgumentException("Driver [{$driver}] not supported."),
        };

        return $this->drivers[$driver] = new $driverClass($config);
    }

    /**
     * Generate content using AI based on the provided prompt.
     *
     * @param string $prompt
     * @param array $options
     * @return string
     */
    public function generateContent(string $prompt, array $options = []): string
    {
        return $this->driver->generateContent($prompt, $options);
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
        $this->driver = $this->createDriver($driver, []);
    }
}
